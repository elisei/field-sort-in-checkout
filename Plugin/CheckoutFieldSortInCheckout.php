<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace O2TI\FieldSortInCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use O2TI\FieldSortInCheckout\Helper\Config;
use O2TI\FieldSortInCheckout\Model\ChangesSortOrder;

/**
 *  CheckoutFieldSortInCheckout - Change Components.
 */
class CheckoutFieldSortInCheckout
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ChangesSortOrder
     */
    private $changesSortOrder;

    /**
     * @param Config           $config
     * @param ChangesSortOrder $changesSortOrder
     */
    public function __construct(
        Config $config,
        ChangesSortOrder $changesSortOrder
    ) {
        $this->config = $config;
        $this->changesSortOrder = $changesSortOrder;
    }

    /**
     * Change Components in Create Account.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function changeCreateAccount(array $jsLayout): ?array
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['identification-step'])) {
            // phpcs:ignore
            $createAccountFields = &$jsLayout['components']['checkout']['children']['steps']['children']['identification-step']['children']['identification']['children']['createAccount']['children']['create-account-fieldset']['children'];
            $createAccountFields = $this->changesSortOrder->changeCreateAccountFieldSortOrder($createAccountFields);
        }

        return $jsLayout;
    }

    /**
     * Change Components in Shipping.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function changeShippingFields(array $jsLayout): ?array
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step'])) {
            // phpcs:ignore
            $shippingFields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $shippingFields = $this->changesSortOrder->changeAddressFieldSortOrder($shippingFields);
        }

        return $jsLayout;
    }

    /**
     * Change Components in Billing.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function changeBillingFields(array $jsLayout): array
    {
        // phpcs:ignore
        foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'] as &$payment) {
            if (isset($payment['children']['form-fields'])) {
                $billingFields = &$payment['children']['form-fields']['children'];
                $billingFields = $this->changesSortOrder->changeAddressFieldSortOrder($billingFields);
            }
        }
        // phpcs:ignore
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['billing-address-form'])) {
            // phpcs:ignore
            $billingAddressOnPage = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']['children'];
            $billingAddressOnPage = $this->changesSortOrder->changeAddressFieldSortOrder($billingAddressOnPage);
        }
        // phpcs:ignore
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['beforeMethods']['children']['billing-address-form'])) {
            // phpcs:ignore
            $billingAddressOnPage = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['beforeMethods']['children']['billing-address-form']['children']['form-fields']['children'];
            $billingAddressOnPage = $this->changesSortOrder->changeAddressFieldSortOrder($billingAddressOnPage);
        }

        return $jsLayout;
    }

    /**
     * Select Components for Change.
     *
     * @param LayoutProcessor $layoutProcessor
     * @param callable        $proceed
     * @param array           $args
     *
     * @return array
     */
    public function aroundProcess(LayoutProcessor $layoutProcessor, callable $proceed, array $args): array
    {
        $jsLayout = $proceed($args);
        if ($this->config->isEnabled()) {
            $jsLayout = $this->changeCreateAccount($jsLayout);
            $jsLayout = $this->changeShippingFields($jsLayout);
            $jsLayout = $this->changeBillingFields($jsLayout);
        }

        return $jsLayout;
    }
}
