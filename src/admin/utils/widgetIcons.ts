import {
  faBars,
  faSquare,
  faPuzzlePiece,
} from "@fortawesome/free-solid-svg-icons";
import type { IconDefinition } from "@fortawesome/fontawesome-svg-core";

const WIDGET_ICON_MAP: Record<string, IconDefinition> = {
  bars: faBars,
  button: faSquare,
};

export function getWidgetIcon(iconClass: string): IconDefinition {
  const name = (iconClass ?? "")
    .split(/\s+/)
    .pop()
    ?.replace(/^fa-/, "")
    ?? "";
  return WIDGET_ICON_MAP[name] ?? faPuzzlePiece;
}
