import { useSelect } from "@wordpress/data";
import React from "react";
import { store } from "../store/admin";
import styles from "./App.module.scss";

export const App = () => {
    const widgets = useSelect(select => select(store).getWidgets(), []);
    console.log(widgets);
    return (
        <div className={styles.app}>
            <h1>Elementrify</h1>
        </div>
    );
};
