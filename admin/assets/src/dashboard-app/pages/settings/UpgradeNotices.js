import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useSelector, useDispatch } from 'react-redux';
import apiFetch from '@wordpress/api-fetch';

const UpgradeNotices = () => {

	if( lmn_admin.pro_available ) {
		return '';
	}

	const dispatch = useDispatch();

	const useUpgradeNotices = useSelector( ( state ) => state.useUpgradeNotices );
	const [ upgradeNoticesState, setUpgradeNoticesState ] = useState( false );

	const updateUpgradeNoticesVisibility = () => {

		setUpgradeNoticesState( 'updating' );

		let assetStatus;
		if ( useUpgradeNotices === false ) {
			assetStatus = true;
		} else {
			assetStatus = false;
		}

		dispatch( { type: 'UPGRADE_NOTICES', payload: assetStatus } );

		const formData = new window.FormData();

		formData.append( 'action', 'ast_disable_pro_notices' );
		formData.append( 'security', lmn_admin.update_nonce );
		formData.append( 'status', assetStatus );

		apiFetch( {
			url: lmn_admin.ajax_url,
			method: 'POST',
			body: formData,
		} ).then( (data) => {
			if ( data.success ) {
				let payloadStatus = __( 'Deactivated!', 'login-me-now' );
				if( assetStatus ) {
					payloadStatus = __( 'Activated!', 'login-me-now' );
				}
				dispatch( { type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: payloadStatus } );
				setUpgradeNoticesState( false );
			}
		} );
	};

	const onGetLoginMeNowPro = ( e ) => {
		if( lmn_admin.pro_installed_status ) {
			const formData = new window.FormData();
			formData.append( 'action', 'login_me_now_recommended_plugin_activate' );
			formData.append( 'security', lmn_admin.plugin_manager_nonce );
			formData.append( 'init', 'login-me-now-addon/login-me-now-addon.php' );
			e.target.innerText = lmn_admin.plugin_activating_text;

			apiFetch( {
				url: lmn_admin.ajax_url,
				method: 'POST',
				body: formData,
			} ).then( ( data ) => {
				if( data.success ) {
					window.open( lmn_admin.login_me_now_base_url, '_self' );
				}
			} );
		} else {
			onUpgradeLinkTrigger();
		}
	};

	const onUpgradeLinkTrigger = () => {
		window.open(
			lmn_admin.upgrade_url,
			'_blank'
		);
	};

	const getLoginMeNowProTitle = () => {
		return lmn_admin.pro_installed_status ? __( 'Activate Now', 'login-me-now' ) : __( 'Upgrade Now', 'login-me-now' );
	}

	return (
		<section className='block px-8 py-8 justify-between'>
			<div className='mr-16 w-full flex flex-col sm:flex-row sm:items-center'>
				<h3 className="p-0 flex-1 justify-right inline-flex text-xl leading-8 font-semibold text-slate-800">
					{ __( 'Build Better Websites with Login Me Now Pro', 'login-me-now' ) }
				</h3>
				<button
					type="button"
					className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-lmn transition focus:bg-lmn-hover hover:bg-lmn-hover focus:outline-none h-9"
					onClick={onGetLoginMeNowPro}
				>
					{ getLoginMeNowProTitle() }
				</button>
			</div>
			<p className="mt-2 w-full md:w-9/12 text-sm text-slate-500 tablet:w-full">
				{
					__(
						`Access powerful features for painless WordPress design without the high costs. Powerful tools, premium support, limitless opportunity with Pro! Toggle upgrade notices on or off `,
						"login-me-now"
					)
				}
				<span onClick={updateUpgradeNoticesVisibility} className='cursor-pointer text-lmn focus:text-lmn-hover active:text-lmn-hover hover:text-lmn-hover' rel="noreferrer">
					{ 'updating' === upgradeNoticesState ? __( 'updating...', 'login-me-now' ) : __( 'here.', 'login-me-now' ) }
				</span>
			</p>
		</section>
	);
};

export default UpgradeNotices;
