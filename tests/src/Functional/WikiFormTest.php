<?php

namespace Drupal\Tests\leo_form_wikipedia\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the form wikipedia search.
 *
 * @group leo_form_wikipedia
 */
class WikiFormTest extends BrowserTestBase {

  public static $modules = ['leo_form_wikipedia'];

  /**
   * Test of paths through the example wizard form.
   */
  public function testWizardForm() {
    $this->drupalGet(Url::fromRoute('leo_form_wikipedia.wiki'));
    $page = $this->getSession()->getPage();
    // Page title as definied in route.yml
    $h1 = $page->find('css', 'h1');
    $this->assertContains('WIKI', $h1->getText(), 'Route wiki failed');

    // Empty querystring gives instructions
    $h2 =  $page->find('css', 'h2');
    $this->assertContains('Instructions', $h2->getText(), ' Instructions not showed in wiki page');

    // Get /wiki/Roosevelt and check page title
    $this->drupalGet(Url::fromRoute('leo_form_wikipedia.wiki',['parameter' => 'Peron']));
    $h1 = $page->find('css', 'h1');
    $this->assertContains('Peron', $h1->getText (), 'Parameter from querystring couldn`t set page title');

    // Instructions hidden when showing wikipedia content
    $h2 =  $page->find('css', 'h2');
    $this->assertNull( $h2, ' Instructions showed in wiki page after searching');

    // Search wikipedia using search form
    $this->submitForm(['search' => 'Clinton'], 'Search');
    $page = $this->getSession()->getPage();
    $wikilink = $page->find('css', '.wikipedia-link');
    $this->assertContains('Clinton', $wikilink->getText (), 'Search form couldn`t set link to wikipedia');

    // Is the form search still filled out?
    $search = $page->findField('search')->getValue();
    $this->assertEquals('Clinton', $search);

    // Page title as definied in route.yml
    $h1 = $page->find('css', 'h1');
    $this->assertContains('WIKI', $h1->getText(), 'Route wiki failed');

    // Instructions hidden when showing wikipedia content
    $h2 =  $page->find('css', 'h2');
    $this->assertNull( $h2, ' Instructions showed in wiki page after searching');

    // Go back to /wiki with no parameters
    $this->drupalGet(Url::fromRoute('leo_form_wikipedia.wiki'));
    $page = $this->getSession()->getPage();

    // Page title as definied in route.yml
    $h1 = $page->find('css', 'h1');
    $this->assertContains('WIKI', $h1->getText(), 'Route wiki failed');

    // Search wikipedia using search form
    $this->submitForm(['search' => 'Maradona'], 'Search');
    $page = $this->getSession()->getPage();
    $wikilink = $page->find('css', '.wikipedia-link');
    $this->assertContains('Maradona', $wikilink->getText (), 'Search form couldn`t set link to wikipedia');
    // Is the form search still filled out?
    $search = $page->findField('search')->getValue();
    $this->assertEquals('Maradona', $search);
  }

}
