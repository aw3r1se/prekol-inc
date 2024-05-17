const defineEntity = ((name, page) => {
    return page.props.dashboard.entities[name];
});

export { defineEntity };
