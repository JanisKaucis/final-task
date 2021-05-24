<?php
namespace App\Services;

class ConnectToBankLVService
{
    /**
     * @var mixed
     */
    private $currencies;

    public function connectToBankLV()
    {
        $bankUrl = 'https://www.bank.lv/vk/ecb.xml';
        $xml = simplexml_load_file($bankUrl);
        $data = json_encode($xml);
        $data = json_decode($data, true);
        $this->currencies = $data['Currencies']['Currency'];
    }


    public function getCurrencies()
    {
        return $this->currencies;
    }
}
