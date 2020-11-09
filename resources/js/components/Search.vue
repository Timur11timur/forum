<template>
    <ais-instant-search :search-client="searchClient" index-name="threads">
        <ais-search-box placeholder="Find a thread" :autofocus="true" />

        <ais-refinement-list attribute="channel.name" />

        <ais-hits>
            <div slot="item" slot-scope="{ item }">
                <h2>
                    <a :href="item.path">
                        <ais-highlight :result="item" attribute-name="title"></ais-highlight>
                        {{ item.title }}
                    </a>
                </h2>
            </div>
        </ais-hits>
    </ais-instant-search>
</template>

<script>
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css';

export default {
    props: ['appid', 'apikey', 'query'],

    data() {
        return {
            searchClient: algoliasearch(
                this.appid,
                this.apikey
            ),
        };
    },
};
</script>
