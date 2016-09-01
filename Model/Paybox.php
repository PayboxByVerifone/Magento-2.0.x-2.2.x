<?php
/**
 * Paybox Epayment module for Magento
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * available at : http://opensource.org/licenses/osl-3.0.php
 *
 * @package    Paybox_Epayment
 * @copyright  Copyright (c) 2013-2014 Paybox
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Paybox\Epayment\Model;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment\Transaction;
use Paybox\Epayment\Model\Payment\AbstractPayment;

class Paybox {
	private $_currencyDecimals = array(
		'008' => 2,
		'012' => 2,
		'032' => 2,
		'036' => 2,
		'044' => 2,
		'048' => 3,
		'050' => 2,
		'051' => 2,
		'052' => 2,
		'060' => 2,
		'064' => 2,
		'068' => 2,
		'072' => 2,
		'084' => 2,
		'090' => 2,
		'096' => 2,
		'104' => 2,
		'108' => 0,
		'116' => 2,
		'124' => 2,
		'132' => 2,
		'136' => 2,
		'144' => 2,
		'152' => 0,
		'156' => 2,
		'170' => 2,
		'174' => 0,
		'188' => 2,
		'191' => 2,
		'192' => 2,
		'203' => 2,
		'208' => 2,
		'214' => 2,
		'222' => 2,
		'230' => 2,
		'232' => 2,
		'238' => 2,
		'242' => 2,
		'262' => 0,
		'270' => 2,
		'292' => 2,
		'320' => 2,
		'324' => 0,
		'328' => 2,
		'332' => 2,
		'340' => 2,
		'344' => 2,
		'348' => 2,
		'352' => 0,
		'356' => 2,
		'360' => 2,
		'364' => 2,
		'368' => 3,
		'376' => 2,
		'388' => 2,
		'392' => 0,
		'398' => 2,
		'400' => 3,
		'404' => 2,
		'408' => 2,
		'410' => 0,
		'414' => 3,
		'417' => 2,
		'418' => 2,
		'422' => 2,
		'426' => 2,
		'428' => 2,
		'430' => 2,
		'434' => 3,
		'440' => 2,
		'446' => 2,
		'454' => 2,
		'458' => 2,
		'462' => 2,
		'478' => 2,
		'480' => 2,
		'484' => 2,
		'496' => 2,
		'498' => 2,
		'504' => 2,
		'504' => 2,
		'512' => 3,
		'516' => 2,
		'524' => 2,
		'532' => 2,
		'532' => 2,
		'533' => 2,
		'548' => 0,
		'554' => 2,
		'558' => 2,
		'566' => 2,
		'578' => 2,
		'586' => 2,
		'590' => 2,
		'598' => 2,
		'600' => 0,
		'604' => 2,
		'608' => 2,
		'634' => 2,
		'643' => 2,
		'646' => 0,
		'654' => 2,
		'678' => 2,
		'682' => 2,
		'690' => 2,
		'694' => 2,
		'702' => 2,
		'704' => 0,
		'706' => 2,
		'710' => 2,
		'728' => 2,
		'748' => 2,
		'752' => 2,
		'756' => 2,
		'760' => 2,
		'764' => 2,
		'776' => 2,
		'780' => 2,
		'784' => 2,
		'788' => 3,
		'800' => 2,
		'807' => 2,
		'818' => 2,
		'826' => 2,
		'834' => 2,
		'840' => 2,
		'858' => 2,
		'860' => 2,
		'882' => 2,
		'886' => 2,
		'901' => 2,
		'931' => 2,
		'932' => 2,
		'934' => 2,
		'936' => 2,
		'937' => 2,
		'938' => 2,
		'940' => 0,
		'941' => 2,
		'943' => 2,
		'944' => 2,
		'946' => 2,
		'947' => 2,
		'948' => 2,
		'949' => 2,
		'950' => 0,
		'951' => 2,
		'952' => 0,
		'953' => 0,
		'967' => 2,
		'968' => 2,
		'969' => 2,
		'970' => 2,
		'971' => 2,
		'972' => 2,
		'973' => 2,
		'974' => 0,
		'975' => 2,
		'976' => 2,
		'977' => 2,
		'978' => 2,
		'979' => 2,
		'980' => 2,
		'981' => 2,
		'984' => 2,
		'985' => 2,
		'986' => 2,
		'990' => 0,
		'997' => 2,
		'998' => 2,
	);

	private $_errorCode = array(
		'00000' => 'Successful operation',
		'00001' => 'Payment system not available',
		'00003' => 'Paybor error',
		'00004' => 'Card number or invalid cryptogram',
		'00006' => 'Access denied or invalid identification',
		'00008' => 'Invalid validity date',
		'00009' => 'Subscription creation failed',
		'00010' => 'Unknown currency',
		'00011' => 'Invalid amount',
		'00015' => 'Payment already done',
		'00016' => 'Existing subscriber',
		'00021' => 'Unauthorized card',
		'00029' => 'Invalid card',
		'00030' => 'Timeout',
		'00033' => 'Unauthorized IP country',
		'00040' => 'No 3-D Secure',
	);

	private $_resultMapping = array(
		'M' => 'amount',
		'R' => 'reference',
		'T' => 'call',
		'A' => 'authorization',
		'B' => 'subscription',
		'C' => 'cardType',
		'D' => 'validity',
		'E' => 'error',
		'F' => '3ds',
		'G' => '3dsWarranty',
		'H' => 'imprint',
		'I' => 'ip',
		'J' => 'lastNumbers',
		'K' => 'sign',
		'N' => 'firstNumbers',
		'O' => '3dsInlistment',
		'o' => 'celetemType',
		'P' => 'paymentType',
		'Q' => 'time',
		'S' => 'transaction',
		'U' => 'subscriptionData',
		'W' => 'date',
		'Y' => 'country',
		'Z' => 'paymentIndex',
	);

	protected $_objectManager = null;
	protected $_urlInterface = null;
	protected $_helper = null;
	protected $_logger = null;
 
	public function __construct(
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\UrlInterface $urlInterface,
		\Paybox\Epayment\Helper\Data $helper,
		\Psr\Log\LoggerInterface $logger
	)
	{
		$this->_objectManager = $objectManager;
		$this->_urlInterface = $urlInterface;
		$this->_helper = $helper;
		$this->_logger = $logger;
	}

	protected function _buildUrl($url) {
		$url = $this->_urlInterface->getUrl($url, array('_secure' => true));
		$url = $this->_urlInterface->sessionUrlVar($url);
		return $url;
	}

	protected function _callDirect($type, $amount, Order $order, Transaction $transaction) {
		$config = $this->getConfig();

		$amountScale = $this->getCurrencyScale($order);
		$amount = round($amount * $amountScale);

		// Transaction information
		$callNumber = $transaction->getAdditionalInformation(AbstractPayment::CALL_NUMBER);
		$transNumber = $transaction->getAdditionalInformation(AbstractPayment::TRANSACTION_NUMBER);

		$version = '00103';
        $password = $config->getPassword();
        if($config->getSubscription() == 'plus'){
            $version = '00104';
            $password = $config->getPasswordplus();
        }

		$now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
		$fields = array(
			'ACTIVITE' => '024',
			'VERSION' => '00103',
			'CLE' => $config->getPassword(),
			'DATEQ' => $now->format('dmYHis'),
			'DEVISE' => sprintf('%03d', $this->getCurrency($order)),
			'IDENTIFIANT' => $config->getIdentifier(),
			'MONTANT' => sprintf('%010d', $amount),
			'NUMAPPEL' => sprintf('%010d', $transNumber),
			'NUMQUESTION' => sprintf('%010d', $now->format('U')),
			'NUMTRANS' => sprintf('%010d', $callNumber),
			'RANG' => sprintf('%02d', $config->getRank()),
			'REFERENCE' => $this->tokenizeOrder($order),
			'SITE' => sprintf('%07d', $config->getSite()),
			'TYPE' => sprintf('%05d', (int)$type),
		);

		// Specific Paypal
		$details = $transaction->getAdditionalInformation(Transaction::RAW_DETAILS);
		switch ($details['cardType']) {
			case 'PAYPAL':
				$fields['ACQUEREUR'] = 'PAYPAL';
				break;
		}

		$urls = $config->getDirectUrls();
		$url = $this->checkUrls($urls);

		// Init client
		$clt = new \Magento\Framework\HTTP\ZendClient($url, array(
			'maxredirects' => 0,
			'useragent' => 'Magento Paybox module',
			'timeout' => 5,
		));
		$clt->setMethod(\Magento\Framework\HTTP\ZendClient::POST);
		$clt->setRawData(http_build_query($fields));

		// Do call
		$response = $clt->request();

		if ($response->isSuccessful()) {
			// Process result
			$result = array();
			parse_str($response->getBody(), $result);
			return $result;
		}

		// Here, there's a problem
		throw new \LogicException(__('Paybox not available. Please try again later.'));
	}

	public function buildSystemParams(Order $order, AbstractPayment $payment) {
		$config = $this->getConfig();

		// URLs
		$baseUrl = 'pbxep/payment';
		$values = array(
			'PBX_ANNULE' => $this->_buildUrl($baseUrl . '/cancel'),
			'PBX_EFFECTUE' => $this->_buildUrl($baseUrl . '/success'),
			'PBX_REFUSE' => $this->_buildUrl($baseUrl . '/failed'),
			'PBX_REPONDRE_A' => $this->_buildUrl($baseUrl . '/ipn'),
		);

		// Merchant information
		$values['PBX_SITE'] = $config->getSite();
		$values['PBX_RANG'] = substr(sprintf('%02d', $config->getRank()), -2);
		$values['PBX_IDENTIFIANT'] = $config->getIdentifier();

		// Card information
		$cards = $payment->getCards();
		if ($payment->getHasCctypes()) {
			$code = $order->getPayment()->getData('cc_type');
		} else {
			$code = array_keys($cards);
			$code = $code[0];
		}

		if (!isset($cards[$code])) {
			$message = 'No card with code %s.';
			throw new \LogicException(__($message, $code));
		}
		$card = $cards[$code];
		$values['PBX_TYPEPAIEMENT'] = $card['payment'];
		$values['PBX_TYPECARTE'] = $card['card'];
		// if ($card['payment'] == 'KWIXO') {
		// 	$kwixo = Mage::getSingleton('pbxep/kwixo');
		// 	$values = $kwixo->buildKwixoParams($order, $values);
		// }

		// Order information
		$values['PBX_PORTEUR'] = $this->getBillingEmail($order);
		$values['PBX_DEVISE'] = $this->getCurrency($order);
		$values['PBX_CMD'] = $this->tokenizeOrder($order);

		// Amount
		$orderAmount = $order->getBaseGrandTotal();
		$amountScale = $this->_currencyDecimals[$values['PBX_DEVISE']];
		$amountScale = pow(10, $amountScale);
		if ($payment->getCode() == 'pbxep_threetime') {
			$amounts = $this->computeThreetimePayments($orderAmount, $amountScale);
			foreach ($amounts as $k => $v) {
				$values[$k] = $v;
			}
		}
		else {
			$values['PBX_TOTAL'] = sprintf('%03d', round($orderAmount * $amountScale));
			switch ($payment->getPayboxAction()) {
				case AbstractPayment::PBXACTION_MANUAL:
					$values['PBX_AUTOSEULE'] = 'O';
					break;

				case AbstractPayment::PBXACTION_DEFERRED:
					$delay = (int) $payment->getConfigData('delay');
					if ($delay < 1) {
						$delay = 1;
					} else if ($delay > 7) {
						$delay = 7;
					}
					$values['PBX_DIFF'] = sprintf('%02d', $delay);
					break;
			}
		}

		// 3-D Secure
		if (!$payment->is3DSEnabled($order)) {
			$values['PBX_3DS'] = 'N';
		}

		// Paybox => Magento
		$values['PBX_RETOUR'] = 'M:M;R:R;T:T;A:A;B:B;C:C;D:D;E:E;F:F;G:G;H:H;I:I;J:J;N:N;O:O;P:P;Q:Q;S:S;W:W;Y:Y;K:K';
		$values['PBX_RUF1'] = 'POST';

		// Choose correct language
		$lang = $this->_objectManager->get('Magento\Framework\Locale\Resolver');
		if (!empty($lang)) {
			$lang = preg_replace('#_.*$#', '', $lang->getLocale());
		}
		$languages = $config->getLanguages();
		if (!array_key_exists($lang, $languages)) {
			$lang = 'default';
		}
		$lang = $languages[$lang];
		$values['PBX_LANGUE'] = $lang;

		// Choose page format depending on browser/devise
		if ($this->_objectManager->get('Paybox\Epayment\Helper\Mobile')->isMobile()) {
			$values['PBX_SOURCE'] = 'XHTML';
		}

		// Misc.
		$values['PBX_TIME'] = date('c');
		$values['PBX_HASH'] = strtoupper($config->getHmacAlgo());

		// Card specific workaround
		if (($card['payment'] == 'LEETCHI') && ($card['card'] == 'LEETCHI')) {
			$values['PBX_EFFECTUE'] .= '?R='.urlencode($values['PBX_CMD']);
			$values['PBX_REFUSE'] .= '?R='.urlencode($values['PBX_CMD']);
		}
		else if (($card['payment'] == 'PREPAYEE') && ($card['card'] == 'IDEAL')) {
			$s =  '?C=IDEAL&P=PREPAYEE';
			$values['PBX_ANNULE'] .= $s;
			$values['PBX_EFFECTUE'] .= $s;
			$values['PBX_REFUSE'] .= $s;
			$values['PBX_REPONDRE_A'] .= $s;
		}

		// Sort parameters for simpler debug
		ksort($values);

		// Sign values
		$sign = $this->signValues($values);

		// Hash HMAC
		$values['PBX_HMAC'] = $sign;

		return $values;
	}

	public function checkUrls(array $urls) {
		// Init client
		$client = new \Magento\Framework\HTTP\ZendClient(null, array(
			'maxredirects' => 0,
			'useragent' => 'Magento Paybox module',
			'timeout' => 5,
		));
		$client->setMethod(\Magento\Framework\HTTP\ZendClient::GET);

		$error = null;
		foreach ($urls as $url) {
			$testUrl = preg_replace('#^([a-zA-Z0-9]+://[^/]+)(/.*)?$#', '\1/load.html', $url);
			$client->setUri($testUrl);

			try {
				$response = $client->request();
				if ($response->isSuccessful()) {
					return $url;
				}
			}
			catch (\LogicException $e) {
				$error = $e;
			}
		}

		// Here, there's a problem
		throw new \LogicException(__('Paybox not available. Please try again later.'));
	}

	public function computeThreetimePayments($orderAmount, $amountScale) {
		$values = array();
		// Compute each payment amount
		$step = round($orderAmount * $amountScale / 3);
		$firstStep = ($orderAmount * $amountScale) - 2 * $step;
		$values['PBX_TOTAL'] = sprintf('%03d', $firstStep);
		$values['PBX_2MONT1'] = sprintf('%03d', $step);
		$values['PBX_2MONT2'] = sprintf('%03d', $step);

		// Payment dates
		$now = new \DateTime();
		$now->modify('1 month');
		$values['PBX_DATE1'] = $now->format('d/m/Y');
		$now->modify('1 month');
		$values['PBX_DATE2'] = $now->format('d/m/Y');


		// Force validity date of card
		$values['PBX_DATEVALMAX'] = $now->format('ym');
		return $values;
	}

	public function convertParams(array $params) {
		$result = array();
		foreach ($this->_resultMapping  as $param => $key) {
			if (isset($params[$param])) {
				$result[$key] = utf8_encode($params[$param]);
			}
		}

		return $result;
	}

	/**
	 * Create transaction ID from Paybox data
	 */
	protected function createTransactionId(array $payboxData) {
		$call = (int) (isset($payboxData['call']) ? $payboxData['call'] : $payboxData['NUMTRANS']);
		$now = new DateTime('now', new DateTimeZone('Europe/Paris'));
		return $call . '/' . $now->format('U');
	}

	public function directCapture($amount, Order $order, Transaction $transaction) {
		return $this->_callDirect(2, $amount, $order, $transaction);
	}

	public function directRefund($amount, Order $order, Transaction $transaction) {
		return $this->_callDirect(14, $amount, $order, $transaction);
	}

	public function getBillingEmail(Order $order) {
		return $order->getCustomerEmail();
	}

	public function getBillingName(Order $order) {
		return trim(preg_replace("/[^-. a-zA-Z0-9]/", " ", $this->_objectManager->get('Magento\Framework\Filter\RemoveAccents')->filter($order->getCustomerName())));
	}

	/**
	 * @return Paybox\Epayment\Model\Config Paybox configuration object
	 */
	public function getConfig() {
		return $this->_objectManager->get('Paybox\Epayment\Model\Config');
	}

	public function getCurrency(Order $order) {
		$currencyMapper = $this->_objectManager->get('Paybox\Epayment\Model\Iso4217Currency');
		$currency = $order->getBaseCurrencyCode();
		return $currencyMapper->getIsoCode($currency);
	}

	public function getCurrencyDecimals($cartOrOrder) {
		return $this->_currencyDecimals[$this->getCurrency($cartOrOrder)];
	}

	public function getCurrencyScale($cartOrOrder) {
		return pow(10, $this->getCurrencyDecimals($cartOrOrder));
	}

	public function getParams($logParams = false, $checkSign = true) {
		// Retrieves data
		$data = file_get_contents('php://input');
		if (empty($data)) {
			$data = $_SERVER['QUERY_STRING'];
		}
		if (empty($data)) {
			throw new \LogicException("Error Processing Request");
			(__('An unexpected error in Paybox call has occured: no parameters.'));
		}

		// Log params if needed
		if ($logParams) {
			$this->logDebug(sprintf('Call params: %s', $data));
		}

		// Check signature if needed
		if ($checkSign) {
			// Extract signature
			$matches = array();
			if (!preg_match('#^(.*)&K=(.*)$#', $data, $matches)) {
				throw new \LogicException("Error Processing Request");
				(__('An unexpected error in Paybox call has occured: missing signature.'));
			}

			// Check signature
			$signature = base64_decode(urldecode($matches[2]));
			$pubkey = file_get_contents(dirname(__FILE__).'/../etc/pubkey.pem');
			$res = (boolean)openssl_verify($matches[1], $signature, $pubkey);

			if (!$res) {
				if (preg_match('#^C=IDEAL&P=PREPAYEE&(.*)&K=(.*)$#', $data, $matches)) {
					$signature = base64_decode(urldecode($matches[2]));
					$res = (boolean) openssl_verify($matches[1], $signature, $pubkey);
				}

				if (!$res) {
					throw new \LogicException("Error Processing Request");
					(__('An unexpected error in Paybox call has occured: invalid signature.'));
				}
			}
		}

		$rawParams = array();
		parse_str($data, $rawParams);

		// Decrypt params
		$params = $this->convertParams($rawParams);
		if (empty($params)) {
			throw new \LogicException(__('An unexpected error in Paybox call has occured.'));
		}

		return $params;
	}

	public function getSystemUrl() {
		$config = $this->getConfig();
		$urls = $config->getSystemUrls();
		if (empty($urls)) {
			$message = 'Missing URL for Paybox system in configuration';
			throw new \LogicException(__($message));
		}

		$url = $this->checkUrls($urls);

		return $url;
	}

	public function getKwixoUrl() {
		$config = $this->getConfig();
		$urls = $config->getKwixoUrls();
		if (empty($urls)) {
			$message = 'Missing URL for Paybox system in configuration';
			throw new \LogicException(__($message));
		}

		$url = $this->checkUrls($urls);

		return $url;
	}

	public function logDebug($message) {
		$this->_logger->debug($message);
	}

	public function logWarning($message) {
		$this->_logger->warning($message);
	}

	public function logError($message) {
		$this->_logger->error($message);
	}

	public function logFatal($message) {
		$this->_logger->critical($message);
	}

	public function signValues(array $values) {
		$config = $this->getConfig();

		// Serialize values
		$query = array();
		foreach ($values as $name => $value) {
			$query[] = $name . '=' . $value;
		}
		$query = implode('&', $query);
		
		// Prepare key
		$hmac = $config->getHmacKey();
		$key = pack('H*', $hmac);
        
		// Sign values
		$sign = hash_hmac($config->getHmacAlgo(), $query, $key);
		if ($sign === false) {
			$errorMsg = 'Unable to create hmac signature. Maybe a wrong configuration.';
			throw new \LogicException(__($errorMsg));
		}

		return strtoupper($sign);
	}

	public function toErrorMessage($code) {
		if (isset($this->_errorCode[$code])) {
			return $this->_errorCode[$code];
		}
		return 'Unknown error '.$code;
	}

	public function tokenizeOrder(Order $order) {
		$reference = array();
		$reference[] = $order->getRealOrderId();
		$reference[] = $this->getBillingName($order);
		$reference = implode(' - ', $reference);
		return $reference;
	}

	/**
	 * Load order from the $token
	 * @param string $token Token (@see tokenizeOrder)
	 * @return Mage_Sales_Model_Order
	 */
	public function untokenizeOrder($token) {
		$parts = explode(' - ', $token, 2);
		if (count($parts) < 2) {
			$message = 'Invalid decrypted token "%s"';
			throw new \LogicException(__($message, $token));
		}

		// Retrieves order
		$order = $this->_objectManager->get('\Magento\Sales\Model\Order')->loadByIncrementId($parts[0]);
		if (empty($order)) {
			$message = 'Not existing order id from decrypted token "%s"';
			throw new \LogicException(__($message, $token));
		}
		if (is_null($order->getId())) {
			$message = 'Not existing order id from decrypted token "%s"';
			throw new \LogicException(__($message, $token));
		}

		$goodName = $this->getBillingName($order);
		if (($goodName != utf8_decode($parts[1])) && ($goodName != $parts[1])) {
			$message = 'Consistency error on descrypted token "%s"';
			throw new \LogicException(__($message, $token));
		}

		return $order;
	}
}
