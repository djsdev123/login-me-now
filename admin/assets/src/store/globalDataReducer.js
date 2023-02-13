const globalDataReducer = (state = {}, action) => {
	let actionType = wp.hooks.applyFilters('login_me_now_dashboard/data_reducer_action', action.type);
	switch (actionType) {
		case 'UPDATE_INITIAL_STATE':
			return {
				...action.payload,
			};
		case 'UPDATE_BLOCK_STATUSES':
			return {
				...state,
				blocksStatuses: action.payload
			};
		case 'UPDATE_INITIAL_STATE_FLAG':
			return {
				...state,
				initialStateSetFlag: action.payload,
			};
		case 'UPDATE_SETTINGS_ACTIVE_NAVIGATION_TAB':
			return {
				...state,
				activeSettingsNavigationTab: action.payload
			};
		case 'UPDATE_ENABLE_LOAD_FONTS_LOCALLY':
			return {
				...state,
				enableLoadFontsLocally: action.payload,
			};
		case 'UPDATE_ENABLE_LOGS':
			return {
				...state,
				enableLogs: action.payload,
			};
		case 'UPDATE_LOGS_EXPIRATION':
			return {
				...state,
				logsExpiration: action.payload,
			};
		case 'UPDATE_ENABLE_PRELOAD_LOCAL_FONTS':
			return {
				...state,
				enablePreloadLocalFonts: action.payload,
			};
		case 'UPDATE_SETTINGS_SAVED_NOTIFICATION':
			return {
				...state,
				settingsSavedNotification: action.payload,
			};
		case 'GENERATE_MAGIC_LINK_POPUP':
			return {
				...state,
				magicLinkPopup: action.payload,
			};
		default:
			return state;
	}
}

export default globalDataReducer;