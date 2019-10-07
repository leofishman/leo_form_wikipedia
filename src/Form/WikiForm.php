<?php

namespace Drupal\leo_form_wikipedia\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class WikiForm extends FormBase {

    /**
     * @var string
     *
     * protected var to store array from wikipedia service
     * in order to use it by submit function o term by url parameter
     *
     */
    protected $markup;

     /**
      * @var boolean
      *
      * protected var to store if search is by form (0) or querystring (1)
      *
      */
     protected $query_type;


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
         $form['description'] = [
           '#type' => 'item',
           '#title' => t('Wikipedia Search'),
           '#markup' => t('<h2> Instructions </h2>You can either enter a value in the form field or provide a url parameter (/wiki/[parameter]).<br />
                            If a URL parameter is provided then the page displays wikipedia articles containing the parameter in the title.<br />
                            If no parameter is provided, then the page displays wikipedia articles for the term provided in the "Search" form field.<br />
                            The page will display the term that is being searched.'),
         ];
       }

      if (!empty($parameter) && (!$this->query_type)){
        $this->getWikiData($parameter);
        // set page title
        $request = \Drupal::request();
        if ($route = $request->attributes->get(\Symfony\Cmf\Component\Routing\RouteObjectInterface::ROUTE_OBJECT)) {
          $route->setDefault('_title', $parameter);
        }
      }

      $form['search'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Search in Wikipedia'),
        '#default_value' => $form_state->getValue('search',$parameter),
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

       $form['extract'] = [
          '#type' => 'markup',
          '#markup' =>  $this->markup,
       ];

       $form['#theme'] = 'leo_form_wikipedia';

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
      $this->query_type = 1;

      // search wikipedia for term
      $this->getWikiData($wiki_term);
      $form_state->setRebuild(True);

      // set page title (Should set search term as page title?
      $request = \Drupal::request();
      if ($route = $request->attributes->get(\Symfony\Cmf\Component\Routing\RouteObjectInterface::ROUTE_OBJECT)) {
        $route->setDefault('_title', 'WIKI');
      }    }

    /*
     *
     * @param string $wiki_term
     *   The term to search using http client
     *
     * @return array
     *   All the data from wikipedia
     */
    private function getWikiData($wiki_term) {
      $client = \Drupal::service('leo_form_wikipedia.client');

      // Search Wikipedia for a page matching the term
      $wiki_data = $client->getResponse($wiki_term);

      // If no match was found, the result will be empty.
      if (empty($wiki_data)) {
        $this->markup = '';
      }

      // markup that contains the extract with
      // a link back to the Wikipedia source document.
      $this->markup = $client->getMarkup($wiki_data);

    }
}