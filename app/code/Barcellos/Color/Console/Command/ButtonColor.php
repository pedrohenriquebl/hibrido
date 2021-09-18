<?php

namespace Barcellos\Color\Console\Command;

use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ButtonColor
 */
class ButtonColor extends Command
{
    const BUTTON_COLOR = 'buttonColor';

    const STORE_ID = 'storeId';

    /**
     * @var WriterInterface
     */
    private $configWriter;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param WriterInterface $configWriter
     * @param StoreManagerInterface $storeManager
     */

    /**
     * @var TypeListInterface $cacheTypeList
     */
    private $cacheTypeList;

    /**
     * @param WriterInterface $configWriter
     * @param StoreManagerInterface $storeManager
     * @param TypeListInterface $cacheTypeList
     */

    /**
     * @var Pool $cacheFrontendPool
     */
    private $cacheFrontendPool;

    /**
     * @param WriterInterface $configWriter
     * @param StoreManagerInterface $storeManager
     * @param TypeListInterface $cacheTypeList
     */

    public function __construct(WriterInterface $configWriter,
                                StoreManagerInterface $storeManager,
                                TypeListInterface $cacheTypeList,
                                Pool $cacheFrontendPool)
    {
        $this->configWriter = $configWriter;
        $this->storeManager = $storeManager;
        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('button-color:change');
        $this->setDescription('Change the color of all buttons.');
        $this->addArgument(
            self::BUTTON_COLOR,
            InputArgument::REQUIRED,
            __('Change the color of buttons - primary class')
        );
        $this->addArgument(
            self::STORE_ID,
            InputArgument::REQUIRED,
            __('Choose the store ID which will receive the new color parameter')
        );
        parent::configure();
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return null|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $color = $input->getArgument(self::BUTTON_COLOR);
        $storeId = $input->getArgument(self::STORE_ID);

        if(ctype_xdigit($color) && strlen($color)==6) {
            if($this->validateStore($storeId)) {
                $output->writeln('<info>Provided name is `' . $color . '`</info>');
                $this->configWriter->save("barcellos/color/buttoncolor", $color, "stores", $storeId );
                $this->flushCache();
                return 1;
            }
            $output->writeln('<error> The store ID is not valid </error>');

        } else {
            $output->writeln('<error>Its not an HEX type of color (ex: FF0000)</error>');
        }
        return 1;
    }

    private function validateStore(int $storeId): bool
    {
        try {
            $this->storeManager->getStore($storeId);
        } catch (LocalizedException $localizedException) {
            return false;
        }
        return true;
    }

    private function flushCache()
    {
        $_types = [
            'config',
            'layout',
            'block_html',
            'collections',
            'reflection',
            'db_ddl',
            'eav',
            'config_integration',
            'config_integration_api',
            'full_page',
            'translate',
            'config_webservice'
        ];

        foreach ($_types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}
