declare module "*.svg" {
    import * as React from "react";
    const content: React.FunctionComponent<React.SVGProps<SVGSVGElement>>;
    export default content;
}