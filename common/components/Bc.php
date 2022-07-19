<?php
namespace common\components;


use yii\base\Component;

/**
 * Class Bc
 * @package common\components
 *
 * https://docs.bnbchain.org/docs/beaconchain/develop/api-reference/dex-api/block-service#apiv1txs
 *
 */
class Bc extends Component
{
    /**
     * Binance Blockchain HOST
     */
    const BC_HOST = 'https://api.binance.org/bc';
    /**
     * Binance Market HOST
     */
    const MARKET_HOST = 'https://dex-atlantic.binance.org';

    /**
     * @var string
     */
    public $address;
    /**
     * @var string
     */
    public $keyName;
    /**
     * @var string
     */
    public $node = 'https://dataseed5.defibit.io:443';

    const SIDE_BUY = 1;
    const SIDE_SELL = 2;

    /**
     * @param int $startHeight
     * @param int $endHeight
     * @return bool|string
     */
    public function blocks($startHeight, $endHeight)
    {
        return $this->_send(self::BC_HOST, '/api/v1/blocks', [
            'startHeight' => $startHeight,
            'endHeight' => $endHeight,
        ]);
    }

    /**
     * @param int $startTime
     * @param int $endTime
     * @return bool|string
     */
    public function txs(int $startTime, int $endTime)
    {
        return $this->_send(self::BC_HOST, '/api/v1/txs', [
            'address' => $this->address,
            'startTime' => $startTime * 1000,
            'endTime' => $endTime * 1000,
        ]);
    }

    /**
     * @brief Аккаунт с балансами
     * @return bool|string
     */
    public function account()
    {
        return $this->_send(self::MARKET_HOST, '/api/v1/account/' . $this->address);
    }

    /**
     * @brief Токены
     * @param int $offset
     * @param int $limit
     * @return bool|string
     */
    public function tokens($offset = 0, $limit = 1000)
    {
        return $this->_send(self::MARKET_HOST, '/api/v1/tokens', [
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    /**
     * @brief Доступные рынки
     * @param int $offset
     * @param int $limit
     * @return bool|string
     */
    public function markets($offset = 0, $limit = 1000)
    {
        return $this->_send(self::MARKET_HOST, '/api/v1/markets', [
            'offset' => $offset,
            'limit' => $limit,
        ]);
    }

    /**
     * @param string|null $symbol
     * @return bool|string
     */
    public function closedOrders($symbol = null)
    {
        $data = [
            'address' => $this->address,
        ];

        if ($symbol) {
            $data['symbol'] = $symbol;
        }

        return $this->_send(self::MARKET_HOST, '/api/v1/orders/closed', $data);
    }

    /**
     * @param string|null $symbol
     * @return bool|string
     */
    public function openedOrders($symbol = null)
    {
        $data = [
            'address' => $this->address,
        ];

        if ($symbol) {
            $data['symbol'] = $symbol;
        }

        return $this->_send(self::MARKET_HOST, '/api/v1/orders/open', $data);
    }

    /**
     * @param string $id
     * @return bool|string
     */
    public function order($id)
    {
        return $this->_send(self::MARKET_HOST, '/api/v1/orders/' . $id);
    }

    public function placeOrder($symbol, $side, $price, $quantity)
    {
        $price = $price * pow(10, 8);
        $quantity = $quantity * pow(10, 8);
        $keyName = $this->keyName;
        $node = $this->node;
        shell_exec("./bnbcli dex order --symbol $symbol --side $side --price $price --qty $quantity --from $keyName --chain-id Binance-Chain-Tigris --node $node -o json");
    }

    /**
     * @param string $host
     * @param string $method
     * @param array $data
     * @return bool|string
     */
    private function _send($host, $method, array $data = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $host . $method . '?' . http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }
}