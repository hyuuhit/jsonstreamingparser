<?php

require_once dirname(__FILE__).'/InMemoryListener.php';

class MultiDocumentsListener extends InMemoryListener {

    private $document_complete_listener;

    public function __construct($l) {
        $this->document_complete_listener = $l;
    }

    public function end_document() {
        $doc = $this->get_json();
        $this->document_complete_listener->document_complete($doc);
    }
}
