import React from 'react'
import { View, Text, StyleSheet } from 'react-native'
import Animated, { event, useAnimatedScrollHandler, useSharedValue } from 'react-native-reanimated'
import Page from '../components/Page';
 
const WORDS = ["what's", "up", "mobile", "devs?"];

export default function InterpolateWithScrollView() {

    const translateX = useSharedValue(0);

    const scrollHandler = useAnimatedScrollHandler((event) => {
        //console.log(event.contentOffset.x);
        translateX.value = event.contentOffset.x;
    })

    return (
        <Animated.ScrollView
            pagingEnabled
            onScroll={scrollHandler}
            scrollEventThrottle={16}
            horizontal 
            style={styles.container}
        >
            {
                WORDS.map((title, index) => {
                    return (
                        <Page 
                            key={index.toString()} 
                            title={title} 
                            index={index} 
                            translateX={translateX} 
                        />
                    );
                })
            }
        </Animated.ScrollView>
    )
}

const styles = StyleSheet.create({
    container : {
        flex : 1,
        backgroundColor: '#fff',
    }
})