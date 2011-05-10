<?php

/**
 * Filter for setting the AuthnContextClassRef in the response.
 *
 * @package simpleSAMLphp
 * @version $Id$
 */
class sspmod_saml_Auth_Process_AuthnContextClassRef extends SimpleSAML_Auth_ProcessingFilter {

	/**
	 * The URI we should set as the AuthnContextClassRef in the login response.
	 *
	 * @var string
	 */
	private $authnContextClassRef;


	/**
	 * Initialize this filter.
	 *
	 * @param array $config  Configuration information about this filter.
	 * @param mixed $reserved  For future use.
	 */
	public function __construct($config, $reserved) {
		parent::__construct($config, $reserved);
		assert('is_array($config)');

		if (!isset($config['AuthnContextClassRef'])) {
			throw new SimpleSAML_Error_Exception('Missing AuthnContextClassRef option in processing filter.');
		}
	}


}