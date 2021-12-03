<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace O2TI\FieldSortInCheckout\Model;

use O2TI\FieldSortInCheckout\Helper\Config;

/**
 *  ChangesSortOrder - Change Compoments for Inputs.
 */
class ChangesSortOrder
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Change Components at Address Fields.
     *
     * @param array $fields
     *
     * @return array
     */
    public function changeAddressFieldSortOrder(array $fields): array
    {
        foreach ($fields as $key => $data) {
            if (in_array('sortOrder', $fields[$key])) {
                if ($fields[$key]['sortOrder']) {
                    $newSortOrder = $this->config->getSortOrderByFieldAddress($key);
                    if ($newSortOrder) {
                        $fields[$key]['sortOrder'] = (int) $newSortOrder;
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * Change Components at Customer Fields.
     *
     * @param array $fields
     *
     * @return array
     */
    public function changeCreateAccountFieldSortOrder(array $fields): array
    {
        foreach ($fields as $key => $data) {
            if (in_array('sortOrder', $fields[$key])) {
                if ($fields[$key]['sortOrder']) {
                    $newSortOrder = $this->config->getSortOrderByFieldCustomer($key);
                    if ($newSortOrder) {
                        $fields[$key]['sortOrder'] = (int) $newSortOrder;
                    }
                }
            }
        }

        return $fields;
    }
}
