import {createRoot, render } from '@wordpress/element';

console.log(createRoot, render)
render(
    <div>Hello</div>,
    document.getElementById('dragnext-admin')
);