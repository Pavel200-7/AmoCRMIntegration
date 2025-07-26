<?php

require_once 'AmoCrmV4Client.php';

/*
 * Инициализирует класс для работы API AmoCRM
 * и переносит интерфейсы работы с ней в дочерние классы
 */
abstract class AmoCrmDescriptor
{
    private AmoCrmV4Client $client;
    private const SUB_DOMAIN = 'pavelyakovlev2000makron';
    private const CLIENT_ID = '277d7587-3e34-42bf-bd36-32abe6172386';
    private const CLIENT_SECRET = 'XyqoW0YMpGgsI1eYiHTpwRMiZm249izUF6L8LCZFGjTMiolGCmrDnUXQ1D1NUUPh';
    private const CODE = 'def50200122dd333fadd2e0cd421c9f46ff014c8021decb21884be5b04b197652d88797f51b56e6f51d60e5216188a303cc720028280e2a688914f8f9e35db5bfbe2a14545933cd651e7dba70d0992df516772f12eab1f3c48462d0d3f5758158de9362a8904920ffb3252b862d28339d47738badc905b61dd66fd316256a6d211b639093ab1a5e4dd935c3ede105b3e7f770cdfe738a9369c98b69e29589742e56819003b262fecfedd3a5a91b018fa26d1feab0b7195e9b21291dd45bf3acb2f1050277201b2398310fed5826229b39af555974d879a490ab97d633f57889bca826e34ff797cc8de94f7b9a93d6a01d7c7139647765241459ac5c446c41bc36c05da64e42775eeba3d72b230b5f9113a8b8919bd01e2239e3575359fe355f01c2c9f261cde6c75a29b799128efba471e34237eb1bd0f656b9a7e2745f65e2da787d3dc6d98ee1fc03131543817fa9fc7465b604a2d365331e2096e72abed4325585493c4217fbe7952dab36fc062996731bbb4f204b07da3ce77685fd4d3debec8585382e742cadf33b64597999446985e36e498eacaeddcdf2ea10d4cfb4335adbd2a1089506daa49c0f0251e84c28754d88a79c3555eb1b294fe02154aa7b305b8588d0d3b72039e993c7c1205afa2ef3330681466dd977eae0dc045e6a54b67cdd2c0c6b06e07c5d4491ac2272f7dae2fc431b210402f03';
    private const REDIRECT_URL = 'https://pavelyakovlev2000makron.amocrm.ru';

    public function __construct()
    {
        $this->client = new AmoCrmV4Client(
            self::SUB_DOMAIN,
            self::CLIENT_ID,
            self::CLIENT_SECRET,
            self::CODE,
            self::REDIRECT_URL
        );
    }

    protected function POSTRequestApi($service, $params = [], $method = "POST")
    {
        return $this->client->POSTRequestApi($service, $params, $method);
    }

    protected function GETRequestApi($service, $params = [])
    {
        return $this->client->GETRequestApi($service, $params);
    }

    protected function GETAll($entity, $custom_params = null)
    {
        return $this->client->GETAll($entity, $custom_params);
    }

}