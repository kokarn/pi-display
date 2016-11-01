'use strict';

const dirsum = require( 'dirsum' );
const fs = require( 'fs' );
const path = require( 'path' );

dirsum.digest( path.join( __dirname + '/../www' ), 'sha1', ( error, hashes ) => {
    if ( error ) {
        throw error;
    }

    fs.writeFile( path.join( __dirname + '/../www/checksum.json' ), '"' + hashes.hash +'"', ( error ) => {
        if ( error ) {
            throw error;
        }

        console.log( 'New checksum written to www/checksum.json' );
    });
});
