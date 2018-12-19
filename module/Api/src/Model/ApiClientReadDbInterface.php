<?php
namespace Api\Model;

/**
 *
 * PLEASE Notice. This set of methods are meant to be for DB read-only operations
 * and will support the following classes:
 *
 * 1) ApiClient
 * 2) ApiClientPermission
 *
 * @author Julio_MOLINERO
 *
 */
interface ApiClientReadDbInterface
{
    /**
     * Return a set of all api clients that we can iterate over.
     *
     * Each entry should be a ApiClient instance.
     *
     * @param boolean $paginated do we need pagination
     * @return ApiClient[]
     */
    public function findAllApiClient( $paginated = false );
    
    /**
     * Return a single api client.
     *
     * @param  int $id Identifier of the record to return.
     * @return ApiClient
     */
    public function findApiClient( $id );
    
    /**
     * Return a single api client permission.
     *
     * @param string $appLogin Application login name identifier of the record to return.
     * @param string $appKey Application Key
     * @param string $uri The URI the client is trying to access
     * @return boolean
     */
    public function findApiClientPermission( $appLogin, $appKey, $uri ): bool;    
}
