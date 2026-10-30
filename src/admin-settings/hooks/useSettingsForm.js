/**
 * Loads a settings section from the REST API and saves it back, with the
 * loading / saving / saved states the earlier screens had.
 */
import { useCallback, useEffect, useRef, useState } from '@wordpress/element';
import { apiGet, apiPost, errorMessage } from '../../shared/api';

/**
 * Forms whose values differ from the loaded or last saved ones.
 */
const unsavedForms = new Set();

/**
 * Whether a settings form has unsaved changes.
 *
 * @return {boolean} Unsaved changes.
 */
export const hasUnsavedChanges = () => unsavedForms.size > 0;

/**
 * Inline style of the elements which were hidden with display: none.
 *
 * @param {boolean} visible Whether the element is visible.
 * @return {Object|undefined} Style.
 */
export const vShow = ( visible ) =>
	visible ? undefined : { display: 'none' };

/**
 * Inline style of the content which is hidden until the saved values load,
 * so the empty defaults are never shown before them. The content keeps its
 * size, the jQuery plugins measure their fields on mount.
 *
 * @param {boolean} loading Whether the values are loading.
 * @return {Object|undefined} Style.
 */
export const vLoading = ( loading ) =>
	loading ? { visibility: 'hidden' } : undefined;

/**
 * Scrolls the saved message into view, as the earlier screens did.
 *
 * @param {string} id Element id.
 */
export const scrollToMessage = ( id = 'save_message' ) => {
	setTimeout( () => {
		const element = document.getElementById( id );
		if ( element ) {
			element.scrollIntoView( { behavior: 'smooth', block: 'end' } );
		}
	} );
};

export default function useSettingsForm(
	path,
	{ savePath = path, prepare = ( data ) => data } = {}
) {
	const [ settings, setSettings ] = useState( {} );
	const [ loaded, setLoaded ] = useState( false );
	const [ loading, setLoading ] = useState( true );
	const [ saving, setSaving ] = useState( false );
	const [ savedMessage, setSavedMessage ] = useState( false );
	const [ error, setError ] = useState( '' );
	// JSON of the loaded or last saved values.
	const baseline = useRef( null );
	const formId = useRef( {} );

	useEffect( () => {
		let active = true;
		apiGet( path )
			.then( ( response ) => {
				if ( active ) {
					baseline.current = JSON.stringify( response.data || {} );
					setSettings( response.data || {} );
					setLoaded( true );
				}
			} )
			.catch( ( e ) => active && setError( errorMessage( e ) ) )
			.finally( () => active && setLoading( false ) );
		return () => {
			active = false;
		};
	}, [ path ] );

	const unsaved =
		null !== baseline.current &&
		JSON.stringify( settings ) !== baseline.current;

	useEffect( () => {
		const id = formId.current;
		if ( unsaved ) {
			unsavedForms.add( id );
		} else {
			unsavedForms.delete( id );
		}
		return () => unsavedForms.delete( id );
	}, [ unsaved ] );

	// Values replaced from a response are the saved ones.
	const replaceSettings = useCallback( ( next ) => {
		if ( 'function' !== typeof next ) {
			baseline.current = JSON.stringify( next || {} );
		}
		setSettings( next );
	}, [] );

	const update = useCallback( ( key, value ) => {
		setSettings( ( current ) => ( { ...current, [ key ]: value } ) );
	}, [] );

	// Checkbox stored as 'on' / ''.
	const toggle = ( key ) => ( event ) =>
		update( key, event.target.checked ? 'on' : '' );

	// Text, textarea and select fields.
	const change = ( key ) => ( event ) => update( key, event.target.value );

	const save = ( extra = {} ) => {
		setSavedMessage( false );
		setError( '' );
		setSaving( true );

		const submitted = JSON.stringify( settings );
		const data = prepare( {
			...settings,
			...( extra && ! extra.nativeEvent ? extra : {} ),
		} );

		return apiPost( savePath, data )
			.then( ( response ) => {
				baseline.current = submitted;
				setSavedMessage( true );
				scrollToMessage();
				return response;
			} )
			.catch( ( e ) => {
				setError( errorMessage( e ) );
				return null;
			} )
			.finally( () => setSaving( false ) );
	};

	return {
		settings,
		setSettings: replaceSettings,
		unsaved,
		update,
		toggle,
		change,
		loaded,
		loading,
		saving,
		savedMessage,
		setSavedMessage,
		error,
		setError,
		save: ( extra ) => save( extra ).catch( () => {} ),
	};
}
