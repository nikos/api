<?php

/**
 * This is the config file for ZfrCors. Just drop this file into your config/autoload folder (don't
 * forget to remove the .dist extension from the file), and configure it as you want
 */

return [
    'zfr_cors' => [
         /**
          * Set the list of allowed origins domain with protocol.
          */
        'allowed_origins' => explode(',', env('API_CORS_ALLOWED_ORIGIN')),

         /**
          * Set the list of HTTP verbs.
          */
        'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

         /**
          * Set the list of headers. This is returned in the preflight request to indicate
          * which HTTP headers can be used when making the actual request
          */
        'allowed_headers' => ['Authorization', 'Content-Type'],
    ],
];
