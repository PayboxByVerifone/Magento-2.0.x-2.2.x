<?php
namespace Paybox\Epayment\Console\Command;

use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\App\State;
use Magento\Store\Model\StoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class OrderSaveCommand extends Command
{
    protected $objectManager;
     protected $_order;
    
    public function __construct(
        ObjectManagerFactory $objectManagerFactory
    ){
        $params = $_SERVER;
        $params[StoreManager::PARAM_RUN_CODE] = 'admin';
        $params[StoreManager::PARAM_RUN_TYPE] = 'store';
        $this->objectManager = $objectManagerFactory->create($params);
        parent::__construct();
    }
    
    protected function configure()
    {
        $this->setName('paybox:saveorder')
            ->setDescription('Test : Call save method for Order')
            ->setDefinition($this->getInputList());
        parent::configure();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->addArguments($input);
        $output->writeln('<info>Starting Paybox SaveOrder Test</info>');
        
        if(!empty($this->_order)){
            $output->writeln("Order = {$this->_order}");
            $order = $this->objectManager->get('Magento\Sales\Model\Order');
            $order->load($this->_order);
            $order->addStatusHistoryComment('toto');
            $order->save();
            $output->writeln("Order Saved");

        }
        
        $output->writeln('<info>End Paybox SaveOrder Test</info>');
        
    }
    
    public function addArguments($input) {
        $this->_order = intval($input->getArgument("order"));
    }
    
    public function getInputList() {
        $inputList = [];
        $inputList[] = new InputArgument('order', InputArgument::OPTIONAL, 'Order Id');
        return $inputList;
    }

}