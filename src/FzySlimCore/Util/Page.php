<?php
namespace FzySlimCore\Util;

class Page extends \FzyUtils\Page {

    /**
     * Creates a page object out of a request object
     * @param \Slim\Http\Request $request
     * @return Page
     */
    public static function createFromRequest(\Slim\Http\Request $request) {
        return new Page($request->get('offset'), $request->get('limit', 10));
    }
}