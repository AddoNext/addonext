import "../store/admin";
import { App } from "./App";
const { render } = wp.element;

const container = document.getElementById('elementrify-admin');
if (container) {
    render(
        <App />,
        container
    );
}