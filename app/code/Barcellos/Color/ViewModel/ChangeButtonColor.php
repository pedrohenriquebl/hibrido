<?php
namespace Barcellos\Color\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ChangeButtonColor implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getColor()
    {
        return $this->scopeConfig->getValue("barcellos/color/buttoncolor", "stores");
    }
}


