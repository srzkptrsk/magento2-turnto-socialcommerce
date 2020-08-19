<?php
/**
 * @category    ClassyLlama
 * @package
 * @copyright   Copyright (c) 2019 Classy Llama Studios, LLC
 */

namespace TurnTo\SocialCommerce\Plugin\Controller\Account;

class CreatePostPlugin
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    private $redirectFactory;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    /**
     * @var TurnTo\SocialCommerce\Helper\Config
     */
    protected $config;

    /**
     * CreatePostPlugin constructor.
     *
     * @param \Magento\Customer\Model\Session                      $customerSession
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     * @param \Magento\Framework\Message\ManagerInterface          $messageManager
     * @param TurnTo\SocialCommerce\Helper\Config                  $config
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \TurnTo\SocialCommerce\Helper\Config $config
    ) {
        $this->customerSession = $customerSession;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->config = $config;
    }

    /**
     * @param \Magento\Customer\Controller\Account\CreatePost $subject
     * @param $result
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function afterExecute(\Magento\Customer\Controller\Account\CreatePost $subject, $result)
    {
        //check for error message on account creation
        $collection = $this->messageManager->getMessages(false);
        $resultRedirectUrl = $this->customerSession->getPdpUrl();
        if (count($collection->getErrors()) > 0 || is_null($resultRedirectUrl) || !$this->config->getSsoEnabled()) {
            return $result;
        }

        //if no errors get PDP from session and redirect
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setUrl($resultRedirectUrl);
        $this->customerSession->setPdpUrl(null);
        return $resultRedirect;
    }
}
