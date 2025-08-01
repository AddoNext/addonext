import { App } from "./App";
const { render } = wp.element

const container = document.getElementById('dragnext-admin');
if (container) {
    render(
        <App />,
        container
    );
}