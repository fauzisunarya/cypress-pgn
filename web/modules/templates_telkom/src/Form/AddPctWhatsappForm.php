<?php

namespace Drupal\templates_telkom\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddPctWhatsappForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'add_template_wa_pct_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $templates = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'template_whatsapp', 'field_workflow_status'=>'workflow_status_approve']);
        $data = [];

        foreach ($templates as $key => $template) {
            $data[$key] = $template->getTitle();
        }

        $form['container'] = [
            '#type' => 'container',
            '#attributes' => [
                'class' => 'd-flex flex-row',
                'id' => 'pct_wa_container',
            ],
        ];

        $form['container']['pct_tem_wa_id'] = [
            '#type' => 'radios',
            '#options' => $data,
            '#title' => $this->t('Pilih template whatsapp'),
            '#ajax' => [
                'event' => 'change',
                'callback' => '::handlePreview',
                'wrapper' => 'pct_wa_preview_container',
            ],
        ];

        if (! empty($form_state->getValues())) {
            $wa = $form_state->getValue('pct_tem_wa_id');
            $form['container']['pct_tem_wa_id']['#default_value'] = $wa;
            $templateSelected = Node::load($wa);
        }

        $form['container']['pct_wa_preview_container'] = [
            '#type' => 'container',
            '#attributes' => [
                'id' => 'pct_wa_preview_container',
                'class' => 'w-50'
            ],
        ];

        $form['container']['pct_wa_preview_container']['preview'] = [
            '#markup' => isset($templateSelected)
            ? "<h5>Preview Template</h5><h6>{$templateSelected->getTitle()}</h6><p>{$templateSelected->get('field_tem_wa_body')->getValue()[0]['value']}</p>"
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
        $id = $form_data['pct_tem_wa_id'];
        $data = Node::load($id);

        if (is_null($data)) {
            $form_state->setErrorByName('pct_tem_wa_id', 'Please pick template wa first');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $pct_id = Drupal::routeMatch()->getParameter('pct_id');
        $form_data = $form_state->getValues();
        $pct_tem_wa_id = $form_data['pct_tem_wa_id'];
        $response = new RedirectResponse(getenv('APP_URL'). "/node/add/product_catalogue_template_wa?destination=/product-catalog/whatsapp/{$pct_id}&pct_id={$pct_id}&tem_wa_id={$pct_tem_wa_id}");
        $response->send();
    }

    public function handlePreview(array $form, FormStateInterface $form_state)
    {
        return $form['container']['pct_wa_preview_container'];
    }
}