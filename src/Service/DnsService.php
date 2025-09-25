<?php
/*
@Author : Alp Toker
This is a service for checking domain and get it's records.
*/
namespace App\Service;

class DnsService
{

    /*
    * @param string $domain
    * @return bool
    * This function checks if the domain is valid by using php's filter_var.
    */
    public function isValidDomain(string $domain): bool
    {
        $trimmedDomain = trim($domain);

        if(empty($trimmedDomain)) {
            return false;
        }

        return filter_var($trimmedDomain, FILTER_VALIDATE_DOMAIN) !== false;
    }

    /*
    * @param string $domain
    * @return bool
    * This function checks if the domain exists by using php's checkdnsrr.
    */
    public function isDomainExists(string $domain): bool
    {
        //checking the domain with every type.
        return checkdnsrr($domain, 'A') || 
               checkdnsrr($domain, 'AAAA') || 
               checkdnsrr($domain, 'MX');
    }

    /*
    * @param string $domain
    * @return array
    * For the iven domain, we get the all records.
    */
    public function getDnsRecords(string $domain): array
    {

        $resultArr = [
            'A' => [],
            'AAAA' => [],
            'MX' => []
        ];

        $aRecs = dns_get_record($domain, DNS_A);
        if ($aRecs) {
            foreach ($aRecs as $record) {
                $resultArr['A'][] = $record['ip'];
            }
        }

        $fourARecs = dns_get_record($domain, DNS_AAAA);
        if ($fourARecs) {
            foreach ($fourARecs as $record) {
                $resultArr['AAAA'][] = $record['ipv6'];
            }
        }

        $mxRecs = dns_get_record($domain, DNS_MX);
        if ($mxRecs) {
            foreach ($mxRecs as $record) {
                $resultArr['MX'][] = [
                    'host' => $record['target'],
                    'priority' => $record['pri']
                ];
            }

            //we also need to sort the MX records by their priorities.
            usort($resultArr['MX'], function ($a, $b) {
                return $b['priority'] - $a['priority'];
            });
        }

        return $resultArr;

    }

}