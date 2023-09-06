<?php

namespace Drupal\templates_telkom\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddPktEmailForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'add_template_email_paket_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $templates = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'template_email', 'field_workflow_status'=>'workflow_status_approve']);
        $data = [];

        foreach ($templates as $key => $template) {
            $data[$key] = $template->getTitle();
        }

        $form['container'] = [
            '#type' => 'container',
            '#attributes' => [
                'class' => 'd-flex flex-row',
                'id' => 'pkt_email_container',
            ],
        ];

        $form['container']['pkt_tem_email_id'] = [
            '#type' => 'radios',
            '#options' => $data,
            '#title' => $this->t('Pilih template email'),
            '#ajax' => [
                'event' => 'change',
                'callback' => '::handlePreview',
                'wrapper' => 'pkt_email_preview_container',
            ],
        ];

        if (! empty($form_state->getValues())) {
            $email = $form_state->getValue('pkt_tem_email_id');
            $form['container']['pkt_tem_email_id']['#default_value'] = $email;
            $templateSelected = Node::load($email);
        }

        $form['container']['pkt_email_preview_container'] = [
            '#type' => 'container',
            '#attributes' => [
                'id' => 'pkt_email_preview_container',
                'class' => 'w-50'
            ],
        ];

        $form['container']['pkt_email_preview_container']['preview'] = [
            '#markup' => isset($templateSelected)
            ? "<h5>Preview Template</h5><h6>{$templateSelected->getTitle()}</h6><p>{$templateSelected->get('field_tem_email_body')->getValue()[0]['value']}</p>"
            : '',
        ];

        $form['actions']['Save'] = [
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => $this->t('Submit') ,
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $form_data = $form_state->getValues();
        foreach ($form_data as $key => $value) {
            $form_value = trim(strip_tags($value));
            $form_state->setValue($key, $form_value);
        }

        $form_data = $form_state->getValues();
        $id = $form_data['pkt_tem_email_id'];
        $data = Node::load($id);

        if (is_null($data)) {
            $form_state->setErrorByName('pkt_tem_email_id', 'Please pick template email first');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $pkt_id = Drupal::routeMatch()->getParameter('pkt_id');
        $form_data = $form_state->getValues();
        $pkt_tem_email_id = $form_data['pkt_tem_email_id'];
        $response = new RedirectResponse(getenv('APP_URL'). "/node/add/paket_template_email?destination=/paket/{$pkt_id}/email&pkt_id={$pkt_id}&tem_email_id={$pkt_tem_email_id}");
        $response->send();
    }

    public function handlePreview(array $form, FormStateInterface $form_state)
    {
        return $form['container']['pkt_email_preview_container'];
    }
}