<?php

namespace Drupal\templates_telkom\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddPctTwitterForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'add_template_twitter_pct_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $templates = Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'template_twitter', 'field_workflow_status'=>'workflow_status_approve']);
        $data = [];

        foreach ($templates as $key => $template) {
            $data[$key] = $template->getTitle();
        }

        $form['container'] = [
            '#type' => 'container',
            '#attributes' => [
                'class' => 'd-flex flex-row',
                'id' => 'pct_twitter_container',
            ],
        ];

        $form['container']['pct_tem_twitter_id'] = [
            '#type' => 'radios',
            '#options' => $data,
            '#title' => $this->t('Pilih template twitter'),
            '#ajax' => [
                'event' => 'change',
                'callback' => '::handlePreview',
                'wrapper' => 'pct_twitter_preview_container',
            ],
        ];

        if (! empty($form_state->getValues())) {
            $twitter = $form_state->getValue('pct_tem_twitter_id');
            $form['container']['pct_tem_twitter_id']['#default_value'] = $twitter;
            $templateSelected = Node::load($twitter);
        }

        $form['container']['pct_twitter_preview_container'] = [
            '#type' => 'container',
            '#attributes' => [
                'id' => 'pct_twitter_preview_container',
                'class' => 'w-50'
            ],
        ];

        $form['container']['pct_twitter_preview_container']['preview'] = [
            '#markup' => isset($templateSelected)
            ? "<h5>Preview Template</h5><h6>{$templateSelected->getTitle()}</h6><p>{$templateSelected->get('field_tem_twitter_body')->getValue()[0]['value']}</p>"
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
        $id = $form_data['pct_tem_twitter_id'];
        $data = Node::load($id);

        if (is_null($data)) {
            $form_state->setErrorByName('pct_tem_twitter_id', 'Please pick template twitter first');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $pct_id = Drupal::routeMatch()->getParameter('pct_id');
        $form_data = $form_state->getValues();
        $pct_tem_twitter_id = $form_data['pct_tem_twitter_id'];
        $response = new RedirectResponse(getenv('APP_URL'). "/node/add/product_catalog_template_twitter?destination=/product-catalog/twitter/{$pct_id}&pct_id={$pct_id}&tem_twitter_id={$pct_tem_twitter_id}");
        $response->send();
    }

    public function handlePreview(array $form, FormStateInterface $form_state)
    {
        return $form['container']['pct_twitter_preview_container'];
    }
}
