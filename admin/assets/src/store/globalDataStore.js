import { createStore } from 'redux';
import globalDataReducer from './globalDataReducer';

const initialState = wp.hooks.applyFilters('login_me_now_dashboard/datastore', {
	initialStateSetFlag: false,
	enableLoadFontsLocally: false,
	enableLogs: true,
	enablePreloadLocalFonts: false,
	enableWhiteLabel: false,
	enableBeta: 'disable',
	settingsSavedNotification: '',
	blocksStatuses: [],
	enableFileGeneration: 'disable',
	activeSettingsNavigationTab: '',
	pluginDescription: '',
	pluginName: '',
	themeScreenshotURL: '',
	themeDescription: '',
	themeName: '',
	agencyLicenseLink: '',
	agencyAuthorURL: '',
	agencyAuthorName: '',
}
);

const globalDataStore = createStore(
	globalDataReducer,
	initialState,
	window.__REDUX_DEVTOOLS_EXTENSION__ &&
	window.__REDUX_DEVTOOLS_EXTENSION__()
);

export default globalDataStore;
