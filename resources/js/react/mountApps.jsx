import { createRoot } from 'react-dom/client';
import { readJsonScript } from './lib/dom.js';
import DashboardApp from './components/dashboard/DashboardApp.jsx';
import HistoryApp from './components/history/HistoryApp.jsx';
import SettingsApp from './components/settings/SettingsApp.jsx';

function mountReactApp(rootId, propsId, Component) {
    const rootElement = document.getElementById(rootId);

    if (!rootElement) {
        return;
    }

    const props = readJsonScript(propsId);

    if (!props) {
        return;
    }

    createRoot(rootElement).render(<Component {...props} />);
}

mountReactApp('dashboard-root', 'dashboard-props', DashboardApp);
mountReactApp('history-root', 'history-props', HistoryApp);
mountReactApp('settings-root', 'settings-props', SettingsApp);
