'use strict';

const dirsum = require( 'dirsum' );
const fs = require( 'fs' );

dirsum.digest( './../www', 'sha1', ( error, hashes ) => {
    if ( error ) {
        throw error;
    }

    fs.writeFile( './../www/checksum.json', '"' + hashes.hash +'"', ( error ) => {
        if ( error ) {
            throw error;
        }
    });
});
