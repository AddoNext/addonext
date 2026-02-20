import { useSelect } from "@wordpress/data";
import React from "react";
import { store } from "../store/admin";

export const App = () => {
    const widgets = useSelect(select => select(store).getWidgets(), []);
    console.log(widgets);
    return (
        <div>
            <h1>Elementrify</h1>
        </div>
    );
};
