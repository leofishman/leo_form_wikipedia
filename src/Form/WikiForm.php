<?php

namespace Drupal\leo_form_wikipedia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class WikiForm extends FormBase {



    /**
     *
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId() {
        return 'leo_form_wikipedia_wiki_form';
    }

    /**
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     *
     * @return array
     *   The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state, $parameter = NULL) {

       if (empty($form_state->getValue('search')) and (empty($parameter))){
         $form['Description'] = [
           '#type' => 'item',
           '#title' => t('Wikipedia Search'),
           '#markup' => t('You can either enter a value in the form field or provide a url parameter (/wiki/[parameter]).<br />
                            If a URL parameter is provided then the page displays wikipedia articles containing the parameter in the title.<br />
                            If no parameter is provided, then the page displays wikipedia articles for the term provided in the "Search" form field.<br />
                            The page will display the term that is being searched.'),
         ];
       }

      $form['search'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Search in Wikipedia'),
        '#default_value' => $parameter,
        '#attributes' => array(
          'placeholder' => t('Retrieve from wikipedia'),
        ),
        '#description' => $this->t('Please enter the term you want to retrieve from wikipedia.'),
        '#required' => TRUE,
      ];

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Search'),
      ];


        return $form;

    }



  /**
     *
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      // Get the term to search
      $wiki_term = $form_state->getValue('search');
    }

}
