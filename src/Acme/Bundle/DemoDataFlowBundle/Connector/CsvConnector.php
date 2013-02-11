<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConnector;

/**
 * Csv connector
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CsvConnector extends AbstractConnector
{

    /**
     * Get configuration
     * @return ConfigurationInterface
     */
    public function getNewConfigurationInstance()
    {
        // TODO : inject existing ?
        return new \Acme\Bundle\DemoDataFlowBundle\Configuration\CsvConfiguration();
    }

    /**
     * Get form
     * @return string
     */
    public function getFormId()
    {
        return "configuration.form.csv";
    }

    /**
     * Get form handler
     * @return string
     */
    public function getFormHandlerId()
    {
        return "oro_dataflow.form.handler.configuration";
    }
}
