<?php
/**
 * @author Rubin Shrestha <rubin.sth@gmail.com>
 */

namespace Rbncha\CompleteOrder\Controller\Adminhtml\Order;
/**
 * Change the status of the order to completed by force
 */
class Completeorder extends \Magento\Sales\Controller\Adminhtml\Order
{
	public function execute()
    {

        $order = $this->_initOrder();
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($order) {
            try {

                $orderState = \Magento\Sales\Model\Order::STATE_COMPLETE;
				$order->setState($orderState)->setStatus(\Magento\Sales\Model\Order::STATE_COMPLETE);
				$order->save();

				/**
				 * Add comment for order and notify customer
				 */
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$orderCommentSender = $objectManager->create(\Magento\Sales\Model\Order\Email\Sender\OrderCommentSender::class);
				$orderCommentSender->send($order, true, 'Order has been completed');

				$this->messageManager->addSuccess(__('Order has been manually processed.'));
                
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $this->messageManager->addError(__('Exception occurred during order load'));
            }
        }

        $resultRedirect->setPath('sales/*/view', array('order_id' => $this->getRequest()->getParam('order_id')));
        return $resultRedirect;
    }

    /**
     * Granting access to anyone who has access to order resource
     */
    protected function _isAllowed()
	{	
		return $this->_authorization->isAllowed(\Magento\Sales\Controller\Adminhtml\Order::ADMIN_RESOURCE);
	}
}