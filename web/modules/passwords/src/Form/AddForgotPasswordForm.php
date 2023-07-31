<?php

namespace Drupal\passwords\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class AddForgotPasswordForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'add_forgot_password_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Your Email'),
            '#attributes' => [
                'class' => ['txt-class'],
            ],
            '#default_value' => '',
        ];

        $form['actions']['Save'] = [
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#attributes' => [
                'class' => ['mt-2'],
            ],
            '#value' => $this->t('Submit') ,
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $form_data = $form_state->getValues();
        foreach ($form_data as $key => $value) {
            $form_value = trim(strip_tags($value));
            $form_state->setValue($key, $form_value);
        }

        $form_data = $form_state->getValues();
        $email = $form_data['email'];

        if (empty($email)) {
            $form_state->setErrorByName('email', 'Please fill email first');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $form_data = $form_state->getValues();

        $email = $form_data['email'];
        $user = user_load_by_mail($email);
        if ($user) {
            $params = [
                'account' => $user
            ];
            $message = [
                'id' => 'user_password_reset',
                'module' => 'user',
                'params' => $params,
                'subject' => '',
                'langcode' => $user->getPreferredLangcode(),
                'body' => [],
            ];

            user_mail('password_reset', $message, $params);

            \Drupal::service('restapi_telkom.app_helper')->sendEmailV2(
                [$user->getEmail()], 
                $message['subject'], 
                ['body' => "<div>{$message['body'][0]}</div>"]
            );
            // $queue = Drupal::service('queue')->get('send_forgot_password_queue');
            // $queue->createItem($user);
            // if (str_contains(substr(PHP_OS, 0, 3), 'WIN')) {
            //     shell_exec("start /b cd {$dir} && drush queue:run send_forgot_password_queue");
            // } else {
            //     exec("cd {$dir} && drush queue:run send_forgot_password_queue");
            //     str_repl
            // }
        }
        $form_state->setRedirect('passwords.forgot.success');
    }
}