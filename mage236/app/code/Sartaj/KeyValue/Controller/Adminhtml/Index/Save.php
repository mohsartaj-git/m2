<?php
/**
 * Save controller class
 *
 * Created By : Sartaj
 */
namespace Sartaj\KeyValue\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Sartaj\KeyValue\Model\KeyValue;

class Save extends \Magento\Backend\App\Action
{

    /**
     * @var KeyValue
     */
    protected $uiExamplemodel;

    /**
     * @var Session
     */
    protected $adminsession;

    /**
     * @param Action\Context $context
     * @param KeyValue           $uiExamplemodel
     * @param Session        $adminsession
     */
    public function __construct(
        Action\Context $context,
        KeyValue $uiExamplemodel,
        Session $adminsession
    ) {
        parent::__construct($context);
        $this->uiExamplemodel = $uiExamplemodel;
        $this->adminsession = $adminsession;
    }

    /**
     * Save blog record action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $entity_id = $this->getRequest()->getParam('entity_id');
            if ($entity_id) {
                $this->uiExamplemodel->load($entity_id);
            }

            $this->uiExamplemodel->setData($data);

            try {
                $this->uiExamplemodel->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->adminsession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    if ($this->getRequest()->getParam('back') == 'add') {
                        return $resultRedirect->setPath('*/*/add');
                    } else {
                        return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->uiExamplemodel->getBlogId(), '_current' => true]);
                    }
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}