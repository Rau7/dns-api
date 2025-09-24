DNS Records Api - Project with Symfony 7.3

Steps:

1- Create a project with skeleton.

2- Framework bundle and routing bundle added.

3- Create a service for domains (we need to separate the logic from the controller). (isValidDomain, isDomainExists, getDnsRecords)

4- Create a Controller for homepage and api endpoint for dns records.

5- All set lets test this with postman

    - http://localhost:8000/api/dns/thisdoesnotexist12345.com


    result = {
                "error": "Domain does not exist",
                "domain": "thisdoesnotexist12345.com"
            }

    - http://localhost:8000/api/dns/google.com

    result = {
            "success": true,
            "domain": "google.com",
            "records": {
                "A": [
                    "142.250.187.174"
                ],
                "AAAA": [
                    "2a00:1450:4017:80f::200e"
                ],
                "MX": [
                    {
                        "host": "smtp.google.com",
                        "priority": 10
                    }
                ]
            }
        }
