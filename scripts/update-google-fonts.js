/**
 * Regenerates assets/data/google-fonts.json from the Google Fonts catalog.
 *
 * Output: { "Family Name": ["100", "400", ...] } with upright weights only.
 * Run with: bun run fonts:update
 */

import { writeFileSync } from 'node:fs';

const response = await fetch( 'https://fonts.google.com/metadata/fonts' );
const text = await response.text();
const data = JSON.parse( text.slice( text.indexOf( '{' ) ) );

const fonts = {};
data.familyMetadataList
	.filter( ( font ) => font.isOpenSource !== false )
	.sort( ( a, b ) => a.family.toLowerCase().localeCompare( b.family.toLowerCase() ) )
	.forEach( ( font ) => {
		const weights = Object.keys( font.fonts )
			.filter( ( key ) => ! key.endsWith( 'i' ) )
			.sort( ( a, b ) => Number( a ) - Number( b ) );
		if ( weights.length ) {
			fonts[ font.family ] = weights;
		}
	} );

writeFileSync( new URL( '../assets/data/google-fonts.json', import.meta.url ), JSON.stringify( fonts ) );
console.log( `Wrote ${ Object.keys( fonts ).length } font families.` );
