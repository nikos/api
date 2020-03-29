# CivicTechHub AIRTABLE TO SQL
# by Franco Morero (https://github.com/francomor)
# This generate to new files: group_inserts.sql and group_topic_inserts.sql

from airtable import Airtable
from sqlalchemy import create_engine, text, Table, Column, Integer, String, MetaData, ForeignKey
from sqlalchemy.exc import SQLAlchemyError
from sqlalchemy.sql import select
from os import environ

API_KEY = environ['AIRTABLE_API_KEY']  # your API KEY
MYSQL_DB_USER = environ['MYSQL_USER']
MYSQL_DB_PASS = environ['MYSQL_PASSWORD']
MYSQL_DB_HOST = environ['MYSQL_HOSTNAME']
MYSQL_DB_PORT = environ['MYSQL_PORT']
MYSQL_DB_SCHEMA = environ['MYSQL_DATABASE']

countries_dict = {}
topics_dict = {}


def main():
    print('- Connecting to MySql Database ' + MYSQL_DB_SCHEMA)
    try:
        mysql_engine = create_engine(
            'mysql+pymysql://' + MYSQL_DB_USER + ':' + MYSQL_DB_PASS + '@'
            + MYSQL_DB_HOST + ':' + MYSQL_DB_PORT + '/' + MYSQL_DB_SCHEMA, echo=False
        )
        mysql_engine._metadata = MetaData(bind=mysql_engine)
        mysql_connection = mysql_engine.connect()
        mysql_group_table = Table('group', mysql_engine._metadata,
                                  Column('id', Integer, primary_key=True),
                                  Column('name', String),
                                  Column('description', String),
                                  Column('country_id', ForeignKey('country.id')),
                                  )
        mysql_group_topic_table = Table('group_topic', mysql_engine._metadata,
                                        Column('topic_id', ForeignKey('topic.id')),
                                        Column('group_id', ForeignKey('group.id')),
                                        )
        mysql_country_table = Table('country', mysql_engine._metadata,
                                    Column('id', Integer, primary_key=True),
                                    Column('name', String),
                                    Column('iso_3166_code', String),
                                    )
        mysql_topic_table = Table('topic', mysql_engine._metadata,
                                  Column('id', Integer, primary_key=True),
                                  Column('topic', String),
                                  )
        print('- Connecting to MySql Database ' + MYSQL_DB_SCHEMA + ' successfully')
    except Exception as exception:
        print('- Error connecting to MySql Database ' + MYSQL_DB_SCHEMA )
        print(exception)
        exit()

    print('- Get tables from Airtable')
    try:
        airtable_groups_table = Airtable('app4FKBWUILUmUsE1', 'Groups', api_key=API_KEY)
        groups_records = airtable_groups_table.get_all()
        airtable_country_table = Airtable('app4FKBWUILUmUsE1', 'Country', api_key=API_KEY)
        airtable_topics_table = Airtable('app4FKBWUILUmUsE1', 'Topics', api_key=API_KEY)
        print('- Get tables from Airtable successfully')
    except Exception as exception:
        print('- Error getting tables from Airtable')
        print(exception)
        exit()

    print('- Clean group and group_topic table')
    try:
        mysql_connection.execute(mysql_group_topic_table.delete())
        mysql_connection.execute(mysql_group_table.delete())
        stmt = text('ALTER TABLE `group` AUTO_INCREMENT = 1')
        mysql_connection.execute(stmt)
    except SQLAlchemyError as sql_alchemy_exception:
        error = str(sql_alchemy_exception.__dict__['orig'])
        print('- Error cleaning group and group_topic table')
        print(error)
        exit()
    print('- Clean group and group_topic table successfully')

    print('- Load countries_dict from country table')
    try:
        s = select([mysql_country_table])
        result = mysql_connection.execute(s)
        for row in result:
            countries_dict[row['id']] = row['name']
        print(countries_dict)
    except SQLAlchemyError as sql_alchemy_exception:
        error = str(sql_alchemy_exception.__dict__['orig'])
        print('- Error loading countries_dict from country table')
        print(error)
        exit()

    print('- Load topics_dict from topic table')
    try:
        s = select([mysql_topic_table])
        result = mysql_connection.execute(s)
        for row in result:
            topics_dict[row['id']] = row['topic']
        print(topics_dict)
    except SQLAlchemyError as sql_alchemy_exception:
        error = str(sql_alchemy_exception.__dict__['orig'])
        print('- Error loading topics_dict from topic table')
        print(error)
        exit()

    print('- Start population tables')
    for record in groups_records:
        if 'Group name' in record['fields']:
            group_id = populate_group_table(
                mysql_connection,
                mysql_group_table,
                record,
                airtable_country_table
            )
            if 'Topics' in record['fields']:
                populate_group_topic_table(
                    mysql_connection,
                    mysql_group_topic_table,
                    record,
                    airtable_topics_table,
                    group_id
                )
    print('- Finish population tables successfully')


def populate_group_table(mysql_connection, mysql_table, group_record, airtable_country_table):
    name = group_record['fields']['Group name']
    description = ""
    country_id = None
    if 'Description' in group_record['fields']:
        description = group_record['fields']['Description']

    if 'Country' in group_record['fields']:
        country_records = airtable_country_table.get(group_record['fields']['Country'][0])

        getted_country_id = get_key_in_countries_dict(country_records['fields']['Name'])
        if getted_country_id:
            country_id = getted_country_id

    insert = mysql_table.insert().values(name=name, description=description, country_id=country_id)
    group_id = insert_in_mysql(mysql_connection, insert)
    return group_id


def populate_group_topic_table(mysql_connection, mysql_table, group_record, airtable_topics_table, group_id):
    for topic_id in group_record['fields']['Topics']:
        topic_records = airtable_topics_table.get(topic_id)
        topic_id = get_key_in_topics_dict(topic_records['fields']['Name'])
        if topic_id:
            insert = mysql_table.insert().values(topic_id=topic_id, group_id=group_id)
            insert_in_mysql(mysql_connection, insert)


def get_key_in_countries_dict(val):
    for key, value in countries_dict.items():
        if val == value:
            return key
    return None


def get_key_in_topics_dict(val):
    for key, value in topics_dict.items():
        if val == value:
            return key
    return None


def insert_in_mysql(db_connection, insert):
    try:
        result = db_connection.execute(insert)
        return result.inserted_primary_key
    except SQLAlchemyError as sql_alchemy_exception:
        error = str(sql_alchemy_exception.__dict__['orig'])
        print(error)
        exit()


if __name__ == "__main__":
    main()
