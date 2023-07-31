<?php

namespace Drupal\knowledges_telkom\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AddWibsProductKnowledge extends FormBase
{
    public function getFormId()
    {
        return 'add_wibs_product_knowledge';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $form['category'] = [
            '#type' => 'entity_autocomplete',
            '#title' => $this->t('Pilih Kategori'),
            '#target_type' => 'taxonomy_term',
            '#selection_settings' => [
                'target_bundles' => ['product_knowledge']
            ],
            '#ajax' => [
                'event' => 'autocompleteclose',
                'callback' => '::handleFilter',
                'wrapper' => 'wibs_pknowledge_container'
            ]
        ];
        $category = $form_state->getValue('category');
        if (! empty($category) && ! is_null($category)) {
            $form['category']['#default_value'] = [$category];
        }

        $form['wibs_pknowledge_container'] = [
            '#type' => 'container',
            '#attributes' => [
                'id' => 'wibs_pknowledge_container',
            ],
        ];

        $form['wibs_pknowledge_container']['wibs_pknowledge_parent'] = [
            '#type' => 'entity_autocomplete',
            '#title' => $this->t('Pilih Product Knowledge'),
            '#target_type' => 'node',
            '#selection_handler' => 'default:node_by_field',
            '#disabled' => empty($category) ? TRUE : FALSE,
            '#selection_settings' => [
                'target_bundles' => ['product_knowledge'],
                'filter' => ['field_pknowledge_category' => $category, 'field_workflow_status'=>'workflow_status_approve'],
            ],
            '#attributes' => [
                'class' => [
                    empty($category) ? 'cursor-not-allowed' : 'cursor-pointer'
                ]
            ]
        ];

        $destination = !empty($_GET['destination']) ? $_GET['destination'] : '';
        $form['destination_hidden'] = [
            '#type' => 'hidden',
            '#value' => $destination, 
        ];

        $form['actions']['Save'] = [
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => $this->t('Submit'),
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
        $category = $form_data['category'];
        $id = $form_data['wibs_pknowledge_parent'];
        $data = Node::load($id);

        if (!isset($category) || is_null($category)) {
            $form_state->setErrorByName('category', 'Please pick Category first');
        }

        if (is_null($data)) {
            $form_state->setErrorByName('wibs_pknowledge_parent', 'Please pick Product Knowledge first');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $wibs_id = Drupal::routeMatch()->getParameter('wibs_id');
        $form_data = $form_state->getValues();
        $wibs_pknowledge_parent = $form_data['wibs_pknowledge_parent'];
        $destination = !empty($form_data['destination_hidden']) ? $form_data['destination_hidden'] : "/wibs/{$wibs_id}/product-knowledges";
        $response = new RedirectResponse(getenv('APP_URL'). "/node/add/wibs_product_knowledge?destination={$destination}&wibs_id={$wibs_id}&wibs_pknowledge_parent={$wibs_pknowledge_parent}");
        $response->send();
    }

    public function handleFilter(array $form, FormStateInterface $form_state)
    {
        return $form['wibs_pknowledge_container'];
    }
}
