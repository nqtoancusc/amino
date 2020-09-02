<?php

/**
 *
 * Open page does not require authentication
 */

abstract class OpenController extends Controller {
    public function authorize() {
	    return true;
    }
}
