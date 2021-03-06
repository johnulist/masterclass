<?php
/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace MasterClass\Controller\Admin;

use PrestaShopBundle\Security\Annotation\AdminSecurity;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use MasterClass\Form\Type\NotificationsForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing/#modern-symfony-controllers
 */
class ExempleController extends FrameworkBundleAdminController
{
    /**
     * @see https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing/#security
     * @AdminSecurity(
     *     "is_granted(['read'], request.get('_legacy_controller'))",
     *     message="You do not have permission to access Exemple page."
     * )
     *
     * @return Response
     */
    public function indexAction()
    {
        $formData = [
            'enable_notifications' => $this->configuration->getBoolean('MC_NOTIFICATIONS', true),
            'stock_limit' => $this->configuration->getInt('MC_STOCK_LIMIT', 0),
            'list_emails' => $this->configuration->get('MC_NOTIFIERS_EMAILS', ''),
        ];

        $form = $this->createForm(NotificationsForm::class, $formData);

        return $this->render('@Modules/masterclass/views/admin/index.html.twig', [
            'layoutTitle' => 'Contrôleur utilisant la nouvelle architecture',
            'help_link' => false,
            'notificationsForm' => $form->createView()
        ]);
    }

    /**
     * @AdminSecurity(
     *     "is_granted(['create', 'update'], request.get('_legacy_controller'))",
     *     message="You do not have permission to change settings."
     * )
     *
     * @return Response
     */
    public function formAction(Request $request)
    {
        $form = $this->createForm(NotificationsForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $this->configuration->set('MC_NOTIFICATIONS', $formData['enable_notifications']);
            $this->configuration->set('MC_STOCK_LIMIT', $formData['stock_limit']);
            $this->configuration->set('MC_NOTIFIERS_EMAILS', $formData['list_emails']);
        }

        return $this->redirectToRoute('masterclass_admin_index');
    }
}
