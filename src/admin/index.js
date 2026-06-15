import "../store/admin";
import { App } from "./App";
const { render } = wp.element;

const container = document.getElementById('veloria-admin');
if (container) {
    render(
        <App />,
        container
    );
}