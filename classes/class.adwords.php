<?php

class Adwords {

  public $keyword;
  public $user;

  public function __construct($keyword) {

    $this->keyword = $keyword;
    $this->user = new AdWordsUser(NULL, AW_MAIL, AW_PASS, AW_TOKEN, "ignored", 'Keywords Informations', AW_KEY_DEMO);

    $this->getAdwordsData();
  }

  public function getAdwordsData() {

    $targetingIdeaService = $this->user->GetService('TargetingIdeaService', ADWORDS_VERSION);

    $selector = new TargetingIdeaSelector();
    $selector->requestType = 'STATS';
    $selector->ideaType = 'KEYWORD';
    $selector->requestedAttributeTypes = array('SEARCH_VOLUME', 'COMPETITION');

    $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);

    $selector->localeCode = 'fr_FR';
    $selector->currencyCode = 'EUR';

    $language = new Language();
    $language->id = '1002';
    $selector->searchParameters[] = new LanguageSearchParameter($language);
    $relatedToQuerySearchParameter = new RelatedToQuerySearchParameter();

    $relatedToQuerySearchParameter->queries = $this->keyword['keyword'];
    $selector->searchParameters[] = $relatedToQuerySearchParameter;

    $page = $targetingIdeaService->get($selector);

    if (sizeof($page->entries) > 0):

      $vol = ($page->entries[0]->data[0]->value->value == 0) ? 5 : $page->entries[0]->data[0]->value->value;
      $comp = (float) round($page->entries[0]->data[1]->value->value, 2);

      Connexion::getInstance()->query("UPDATE keyword SET search = '" . $vol . "', competition = '" . round($comp, 2) . "' WHERE id_keyword = '" . $this->keyword['id_keyword'] . "' ");

    else:

      Connexion::getInstance()->query("UPDATE keyword SET search = '0', competition = '0' WHERE id_keyword = '" . $this->keyword['id_keyword'] . "' ");

    endif;
  }

}
