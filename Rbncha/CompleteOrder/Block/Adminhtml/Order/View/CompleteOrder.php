<?php
/**
 * @author Rubin Shrestha <rubin.sth@gmail.com>
 */
namespace Rbncha\CompleteOrder\Block\Adminhtml\Order\View;

class CompleteOrder
{
    public function beforeGetOrderId(\Magento\Sales\Block\Adminhtml\Order\View $subject)
    {

    	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$request = $objectManager->create('\Magento\Framework\App\Helper\Context')->getRequest();
    	$order = $objectManager->create('\Magento\Sales\Model\Order')->load($request->getParam('order_id'));

    	if($order->getStatus() == \Magento\Sales\Model\Order::STATE_COMPLETE) return null;

        $subject->addButton(
                'completeorder',
                [
                	'label' => __('Complete The Order'), 
                	'onclick' => 'setLocation(\'' . $this->getCustomUrl() . '\')',
                	'class' => 'reset'
                ],
                -1
            );

        return null;
    }

    public function getCustomUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $urlManager = $objectManager->get('\Magento\Backend\Helper\Data');
        return $urlManager->getUrl('rsales/order/completeorder');
    }
}