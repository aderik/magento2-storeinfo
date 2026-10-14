<?php declare(strict_types=1);

/**
 * Siteation - https://siteation.dev/
 * Copyright © Siteation. All rights reserved.
 * See LICENSE file for details.
 */

namespace Siteation\StoreInfo\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Siteation\StoreInfo\ViewModel\StoreInfo;
use Siteation\StoreInfo\ViewModel\StoreInfoHours;
use Siteation\StoreInfo\ViewModel\StoreInfoNotice;
use Siteation\StoreInfo\ViewModel\StoreInfoSocials;

/**
 * Resolves storeConfig.store_information from the same view models the templates use,
 * so GraphQL and PHTML always expose the same values for the current store.
 */
class StoreInformation implements ResolverInterface
{
    public function __construct(
        private readonly StoreInfo $storeInfo,
        private readonly StoreInfoHours $hours,
        private readonly StoreInfoSocials $socials,
        private readonly StoreInfoNotice $notice
    ) {
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, ?array $value = null, ?array $args = null): array
    {
        $openingHours = [];
        if ($this->hours->enabled()) {
            foreach ($this->hours->getOpeningHours() as $item) {
                $openingHours[] = [
                    'day' => (string) ($item['day'] ?? ''),
                    'hours' => (string) ($item['hours'] ?? ''),
                ];
            }
        }

        return [
            'name' => $this->storeInfo->getStoreName(),
            'phone' => $this->storeInfo->getPhone(),
            'extra_phone' => $this->storeInfo->getExtraPhone(),
            'whatsapp' => $this->storeInfo->getWhatsApp(),
            'email' => $this->storeInfo->getEmail(),
            'sales_email' => $this->storeInfo->getSalesEmail(),
            'support_email' => $this->storeInfo->getSupportEmail(),
            'street_line1' => $this->storeInfo->getStreetLine1(),
            'street_line2' => $this->storeInfo->getStreetLine2(),
            'postcode' => $this->storeInfo->getPostcode(),
            'city' => $this->storeInfo->getCity(),
            'region' => $this->storeInfo->getRegion(),
            'country_id' => $this->storeInfo->getCountryId(),
            'country' => $this->storeInfo->getCountry(),
            'vat_number' => $this->storeInfo->getVatNumber(),
            'coc_number' => $this->storeInfo->getCoCNumber(),
            'iban_number' => $this->storeInfo->getIbanNumber(),
            'hours' => $this->storeInfo->getHours(),
            'opening_hours' => $openingHours,
            'notice' => $this->notice->getNotices(),
            'socials' => $this->socials->getList(),
        ];
    }
}
