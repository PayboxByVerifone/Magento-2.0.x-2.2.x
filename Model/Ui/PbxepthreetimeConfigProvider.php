<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paybox\Epayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
// use Magento\Framework\App\Config\ScopeConfigInterface;
// use Magento\Framework\View\Asset\Source;
use \Magento\Framework\ObjectManagerInterface;
use Paybox\Epayment\Gateway\Http\Client\ClientMock;
use Paybox\Epayment\Model\Ui\PbxepthreetimeConfig;

/**
 * Class ConfigProvider
 */
final class PbxepthreetimeConfigProvider implements ConfigProviderInterface {

    const CODE = 'pbxep_threetime';

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig() {
        return [
            'payment' => [
                self::CODE => [
                    'cards' => $this->getCards()
                ]
            ]
        ];
    }

    public function getCards() {
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $pbxepthreetimeConfig = $object_manager->get('Paybox\Epayment\Model\Ui\PbxepthreetimeConfig');
        $assetSource = $object_manager->get('Magento\Framework\View\Asset\Source');
        $assetRepository = $object_manager->get('Magento\Framework\View\Asset\Repository');

        $cards = [];
        $types = $pbxepthreetimeConfig->getCards();
        if (!is_array($types)) {
            $types = explode(',', $types);
        }
        foreach ($types as $code) {
            $asset = $assetRepository->createAsset('Paybox_Epayment::images/' . strtolower($code) . '.45.png');
            $placeholder = $assetSource->findRelativeSourceFilePath($asset);
            if ($placeholder) {
                list($width, $height) = getimagesize($asset->getSourceFile());
                $cards[] = [
                    'value' => $code,
                    'url' => $asset->getUrl(),
                    'title' => $code,
                    'width' => $width,
                    'height' => $height
                ];
            }
        }
        return $cards;
    }

}
