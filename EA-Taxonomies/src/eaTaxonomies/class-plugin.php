<?php
class eaTaxonomies_Plugin
{
 
    /**
     * The basename of the plugin.
     *
     * @var string
     */
    private $basename;

 
    /**
     * Flag to track if the plugin is loaded.
     *
     * @var bool
     */
    private $loaded;
 
    /**
     * The plugin router.
     *
     * @var eaTaxonomies_Routing_Router
     */
    private $router;

    /**
     * The plugin event manager.
     *
     * @var eaTaxonomies_EventManagement_EventManager
     */
    private $event_manager;

 
    /**
     * Constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->basename = plugin_basename($file);
        $this->event_manager = new eaTaxonomies_EventManagement_EventManager();
        $this->loaded = false;
        $this->router = new eaTaxonomies_Routing_Router();
    }
 
    /**
     * Loads the plugin into WordPress.
     */
    public function load()
    {
        if ($this->loaded) {
            return;
        }
 
        foreach ($this->get_routes() as $route) {
            $this->router->add_route($route);
        }
 
        foreach ($this->get_subscribers() as $subscriber) {
            $this->event_manager->add_subscriber($subscriber);
        }
 
        $this->loaded = true;
    }

    /**
     * Get the plugin routes.
     *
     * @return eaTaxonomies_Routing_Route[]
     */
    private function get_routes()
    {
        return $this->event_manager->filter('eaTaxonomies_routes', array(
            'route 1', 'route 2'
        ));
    }
 
    /**
     * Get the plugin event subscribers.
     *
     * @return eaTaxonomies_EventManagement_SubscriberInterface[]
     */
    private function get_subscribers()
    {
        return $this->event_manager->filter('eaTaxonomies_subscribers', array(
            new eaTaxonomies_Subscriber_CustomPostTypeSubscriber(),
        ));
    }
}