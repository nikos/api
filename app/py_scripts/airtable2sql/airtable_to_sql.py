# CivicTechHub AIRTABLE TO SQL
# by Franco Morero (https://github.com/francomor)
# This generate to new files: group_inserts.sql and group_topic_inserts.sql
from airtable import Airtable

API_KEY = ''  # your API KEY

countries_dict = {
    '0': 'Global',
    '1': 'Andorra',
    '2': 'United Arab Emirates',
    '3': 'Afghanistan',
    '4': 'Antigua and Barbuda',
    '5': 'Anguilla',
    '6': 'Albania',
    '7': 'Armenia',
    '8': 'Netherlands Antilles',
    '9': 'Angola',
    '10': 'Antarctica',
    '11': 'Argentina',
    '12': 'American Samoa',
    '13': 'Austria',
    '14': 'Australia',
    '15': 'Aruba',
    '16': 'Åland Islands',
    '17': 'Azerbaijan',
    '18': 'Bosnia and Herzegovina',
    '19': 'Barbados',
    '20': 'Bangladesh',
    '21': 'Belgium',
    '22': 'Burkina Faso',
    '23': 'Bulgaria',
    '24': 'Bahrain',
    '25': 'Burundi',
    '26': 'Benin',
    '27': 'Bermuda',
    '28': 'Brunei Darussalam',
    '29': 'Bolivia',
    '30': 'Brazil',
    '31': 'Bahamas',
    '32': 'Bhutan',
    '33': 'Bouvet Island',
    '34': 'Botswana',
    '35': 'Belarus',
    '36': 'Belize',
    '37': 'Canada',
    '38': 'Cocos (Keeling) Islands',
    '39': 'The Democratic Republic Of The Congo',
    '40': 'Central African',
    '41': 'Republic of the Congo',
    '42': 'Switzerland',
    '43': 'Côte d\'Ivoire',
    '44': 'Cook Islands',
    '45': 'Chile',
    '46': 'Cameroon',
    '47': 'China',
    '48': 'Colombia',
    '49': 'Costa Rica',
    '50': 'Serbia and Montenegro',
    '51': 'Cuba',
    '52': 'Cape Verde',
    '53': 'Christmas Island',
    '54': 'Cyprus',
    '55': 'Czech Republic',
    '56': 'Germany',
    '57': 'Djibouti',
    '58': 'Denmark',
    '59': 'Dominica',
    '60': 'Dominican Republic',
    '61': 'Algeria',
    '62': 'Ecuador',
    '63': 'Estonia',
    '64': 'Egypt',
    '65': 'Western Sahara',
    '66': 'Eritrea',
    '67': 'Spain',
    '68': 'Ethiopia',
    '69': 'Finland',
    '70': 'Fiji',
    '71': 'Falkland Islands',
    '72': 'Federated States of Micronesia',
    '73': 'Faroe Islands',
    '74': 'France',
    '75': 'Gabon',
    '76': 'United Kingdom',
    '77': 'Grenada',
    '78': 'Georgia',
    '79': 'French Guiana',
    '80': 'Ghana',
    '81': 'Gibraltar',
    '82': 'Greenland',
    '83': 'Gambia',
    '84': 'Guinea',
    '85': 'Guadeloupe',
    '86': 'Equatorial Guinea',
    '87': 'Greece',
    '88': 'South Georgia and the South Sandwich Islands',
    '89': 'Guatemala',
    '90': 'Guam',
    '91': 'Guinea-Bissau',
    '92': 'Guyana',
    '93': 'Hong Kong',
    '94': 'Heard Island and McDonald Islands',
    '95': 'Honduras',
    '96': 'Croatia',
    '97': 'Haiti',
    '98': 'Hungary',
    '99': 'Indonesia',
    '100': 'Ireland',
    '101': 'Israel',
    '102': 'Isle of Man',
    '103': 'India',
    '104': 'British Indian Ocean Territory',
    '105': 'Iraq',
    '106': 'Islamic Republic of Iran',
    '107': 'Iceland',
    '108': 'Italy',
    '109': 'Jamaica',
    '110': 'Jordan',
    '111': 'Japan',
    '112': 'Kenya',
    '113': 'Kyrgyzstan',
    '114': 'Cambodia',
    '115': 'Kiribati',
    '116': 'Comoros',
    '117': 'Saint Kitts and Nevis',
    '118': 'Democratic People\'s Republic of Korea',
    '119': 'Republic of Korea',
    '120': 'Kuwait',
    '121': 'Cayman Islands',
    '122': 'Kazakhstan',
    '123': 'Lao People\'s Democratic Republic',
    '124': 'Lebanon',
    '125': 'Saint Lucia',
    '126': 'Liechtenstein',
    '127': 'Sri Lanka',
    '128': 'Liberia',
    '129': 'Lesotho',
    '130': 'Lithuania',
    '131': 'Luxembourg',
    '132': 'Latvia',
    '133': 'Libyan Arab Jamahiriya',
    '134': 'Morocco',
    '135': 'Monaco',
    '136': 'Republic of Moldova',
    '137': 'Madagascar',
    '138': 'Marshall Islands',
    '139': 'The Former Yugoslav Republic of Macedonia',
    '140': 'Mali',
    '141': 'Myanmar',
    '142': 'Mongolia',
    '143': 'Macao',
    '144': 'Northern Mariana Islands',
    '145': 'Martinique',
    '146': 'Mauritania',
    '147': 'Montserrat',
    '148': 'Malta',
    '149': 'Mauritius',
    '150': 'Maldives',
    '151': 'Malawi',
    '152': 'Mexico',
    '153': 'Malaysia',
    '154': 'Mozambique',
    '155': 'Namibia',
    '156': 'New Caledonia',
    '157': 'Niger',
    '158': 'Norfolk Island',
    '159': 'Nigeria',
    '160': 'Nicaragua',
    '161': 'Netherlands',
    '162': 'Norway',
    '163': 'Nepal',
    '164': 'Nauru',
    '165': 'Niue',
    '166': 'New Zealand',
    '167': 'Oman',
    '168': 'Panama',
    '169': 'Peru',
    '170': 'French Polynesia',
    '171': 'Papua New Guinea',
    '172': 'Philippines',
    '173': 'Pakistan',
    '174': 'Poland',
    '175': 'Saint-Pierre and Miquelon',
    '176': 'Pitcairn',
    '177': 'Puerto Rico',
    '178': 'Occupied Palestinian Territory',
    '179': 'Portugal',
    '180': 'Palau',
    '181': 'Paraguay',
    '182': 'Qatar',
    '183': 'Réunion',
    '184': 'Romania',
    '185': 'Russian Federation',
    '186': 'Rwanda',
    '187': 'Saudi Arabia',
    '188': 'Solomon Islands',
    '189': 'Seychelles',
    '190': 'Sudan',
    '191': 'Sweden',
    '192': 'Singapore',
    '193': 'Saint Helena',
    '194': 'Slovenia',
    '195': 'Svalbard and Jan Mayen',
    '196': 'Slovakia',
    '197': 'Sierra Leone',
    '198': 'San Marino',
    '199': 'Senegal',
    '200': 'Somalia',
    '201': 'Suriname',
    '202': 'South Sudan',
    '203': 'Sao Tome and Principe',
    '204': 'El Salvador',
    '205': 'Syrian Arab Republic',
    '206': 'Swaziland',
    '207': 'Turks and Caicos Islands',
    '208': 'Chad',
    '209': 'French Southern Territories',
    '210': 'Togo',
    '211': 'Thailand',
    '212': 'Tajikistan',
    '213': 'Tokelau',
    '214': 'Timor-Leste',
    '215': 'Turkmenistan',
    '216': 'Tunisia',
    '217': 'Tonga',
    '218': 'Turkey',
    '219': 'Trinidad and Tobago',
    '220': 'Tuvalu',
    '221': 'Taiwan',
    '222': 'United Republic Of Tanzania',
    '223': 'Ukraine',
    '224': 'Uganda',
    '225': 'United States Minor Outlying Islands',
    '226': 'United States',
    '227': 'Uruguay',
    '228': 'Uzbekistan',
    '229': 'Vatican City State',
    '230': 'Saint Vincent and the Grenadines',
    '231': 'Venezuela',
    '232': 'British Virgin Islands',
    '233': 'U.S. Virgin Islands',
    '234': 'Vietnam',
    '235': 'Vanuatu',
    '236': 'Wallis and Futuna',
    '237': 'Samoa',
    '238': 'Yemen',
    '239': 'Mayotte',
    '240': 'South Africa',
    '241': 'Zambia',
    '242': 'Zimbabwe',
}

topics_dict = {
    '1': 'Medical',
    '2': 'Software',
    '3': 'Digital',
    '4': 'Solution',
    '5': 'Collaboration',
    '6': 'Data/Information',
    '7': 'Support',
}


def get_key_in_countries_dict(val):
    for key, value in countries_dict.items():
        if val == value:
            return key
    return '0'


def get_key_in_topics_dict(val):
    for key, value in topics_dict.items():
        if val == value:
            return key
    return None


groups_table = Airtable('app4FKBWUILUmUsE1', 'Groups', api_key=API_KEY)
groups_records = groups_table.get_all()
country_table = Airtable('app4FKBWUILUmUsE1', 'Country', api_key=API_KEY)
topics_table = Airtable('app4FKBWUILUmUsE1', 'Topics', api_key=API_KEY)
file_group_inserts = open('group_inserts.sql', 'w')
file_group_topic_inserts = open('group_topic_inserts.sql', 'w')

id_counter = 0
for record in groups_records:
    if 'Group name' in record['fields']:
        group_insert = "INSERT INTO `group` (`id`, `name`, `description`, `country_id`) " \
                 "VALUES (" + str(id_counter) + ", `" + record['fields']['Group name'] + '`, `'
        if 'Description' in record['fields']:
            group_insert += record['fields']['Description']
        else:
            group_insert += "NULL"
        group_insert += '`, `'
        if 'Country' in record['fields']:
            country_records = country_table.get(record['fields']['Country'][0])
            country_id = get_key_in_countries_dict(country_records['fields']['Name'])
            group_insert += country_id
        else:
            group_insert += '0'
        group_insert += '`);'

        if 'Topics' in record['fields']:
            for topic_id in record['fields']['Topics']:
                topic_records = topics_table.get(topic_id)
                topic_id = get_key_in_topics_dict(topic_records['fields']['Name'])
                if topic_id:
                    group_topic_insert = "INSERT INTO `group_topic` (`topic_id`, `group_id`) " \
                                         "VALUES (" + str(topic_id) + ", " + str(id_counter) + ");"
                    file_group_topic_inserts.write(group_topic_insert + '\n')

        file_group_inserts.write(group_insert + '\n')
        id_counter += 1

file_group_topic_inserts.close()
file_group_inserts.close()
