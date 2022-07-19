<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Class BcController
 * @package console\controllers
 *
 * Шпаргалка для отправки токенов
 * ./bnbcli send --from from-key-name --to to-address --amount 200000000:BNB --chain-id Binance-Chain-Tigris --node  https://dataseed5.defibit.io:443 --json --memo "Test transfer"
 */
class BcController extends Controller
{
    public function actionBlocks($startHeight, $endHeight)
    {
        $response = Yii::$app->bc->blocks($startHeight, $endHeight);
        $result = json_decode($response);
        print_r($result); die;
    }

    public function actionTxs($from, $to)
    {
        $response = Yii::$app->bc->txs(strtotime($from), strtotime($to . ' +1 day'));
        $result = json_decode($response);
        print_r($result); die;
    }

    public function actionAccount()
    {
        $response = Yii::$app->bc->account();
        $result = json_decode($response);
        print_r($result); die;
    }

    public function actionTokens($offset = 0)
    {
        $response = Yii::$app->bc->tokens($offset);
        $result = json_decode($response);
        print_r($result); die;
    }

    public function actionMarkets($offset = 0)
    {
        $response = Yii::$app->bc->markets($offset);
        $result = json_decode($response);
        print_r($result); die;
    }

    public function actionClosedOrders()
    {
        $response = Yii::$app->bc->closedOrders();
        $result = json_decode($response);
        print_r($result); die;
    }

    public function actionOpenedOrders()
    {
        $response = Yii::$app->bc->openedOrders();
        $result = json_decode($response);
        print_r($result); die;
    }
}