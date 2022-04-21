import { StyleSheet, Text, View } from 'react-native'
import React, { useCallback, useState } from 'react'
import Icon from 'react-native-vector-icons/FontAwesome';
import Animated, { interpolate, runOnJS, useAnimatedGestureHandler, useAnimatedStyle, useSharedValue, withSpring, withTiming } from 'react-native-reanimated';
import { GestureHandlerRootView, PanGestureHandler } from 'react-native-gesture-handler';

const ICON_SIZE = 20;

const clamp = (value,min,max) => {
    'worklet';
    return Math.min(Math.max(value,min), max);
};

const BUTTON_WIDTH = 170;

const MAX_SLIDE_OFFSET = BUTTON_WIDTH * 0.3;

const SlidingCounter = () => {

    const translateX = useSharedValue(0);
    const translateY = useSharedValue(0);

    const [count, setCount] = useState(0); 

    //wrapper function
    const incrementCount = useCallback(() => {
        //external library function
        setCount((currentCount) => currentCount + 1 );
    },[]);

    const decrementCount = useCallback(() => {
        //external library function
        setCount((currentCount) => currentCount - 1 );
    },[]);

    const resetCount = useCallback(() => {
        //external library function
        setCount(0);
    },[]);

    const onPanGestureEvent = useAnimatedGestureHandler({
        onActive : (event) => {
            //console.log(event);
            translateX.value = clamp(
                event.translationX,
                -MAX_SLIDE_OFFSET,
                MAX_SLIDE_OFFSET
            );
            
            translateY.value = clamp(event.translationY, 0, MAX_SLIDE_OFFSET) ;
        },
        onEnd : () => {

            if(translateX.value === MAX_SLIDE_OFFSET){//Increment
                runOnJS(incrementCount)();
            }else if(translateX.value === -MAX_SLIDE_OFFSET){//decrement
                runOnJS(decrementCount)();
            }
            else if(translateY.value === MAX_SLIDE_OFFSET){
                runOnJS(resetCount)();
            }

            translateX.value = withSpring(0);
            translateY.value = withSpring(0);
        }
    });

    const animatedStyle = useAnimatedStyle(() => {

        return {
            transform : [
                { translateX : translateX.value},
                { translateY : translateY.value}
            ]
        }
    },[]);

    const animatedPlusMinusIconStyle = useAnimatedStyle(() => {

        const opacityX = interpolate(
            translateX.value,
            [-MAX_SLIDE_OFFSET, 0, MAX_SLIDE_OFFSET],
            [0.4, 0.8, 0.4]
        );

        const opacityY = interpolate(
            translateY.value,
            [0, MAX_SLIDE_OFFSET],
            [1, 0]
        );

        return {
            opacity : opacityX * opacityY
        }
    },[]);

    const animatedCloseIconStyle = useAnimatedStyle(() => {

        const opacity = interpolate(
            translateY.value,
            [0, MAX_SLIDE_OFFSET],
            [0, 0.8]
        );

        return {
            opacity : opacity
        }
    },[]);

    return (
        <GestureHandlerRootView>
            <View style={styles.button}>
                <Animated.View style={animatedPlusMinusIconStyle}>
                    <Icon 
                        name="minus" 
                        size={ICON_SIZE} 
                        color="white" 
                    />
                </Animated.View>
                <Animated.View style={animatedCloseIconStyle}>
                    <Icon 
                        name="close" 
                        size={ICON_SIZE} 
                        color="white"
                    />
                </Animated.View>
                <Animated.View style={animatedPlusMinusIconStyle}>
                    <Icon 
                        name="plus" 
                        size={ICON_SIZE} 
                        color="white" 
                    />
                </Animated.View>
                <View 
                    style={{
                        ...StyleSheet.absoluteFillObject,
                        justifyContent : 'center',
                        alignItems : 'center'
                    }} 
                >
                    <PanGestureHandler onGestureEvent={onPanGestureEvent}>
                        <Animated.View style={[styles.circle,animatedStyle]} >
                            <Text style={styles.countText}>{count}</Text>
                        </Animated.View>
                    </PanGestureHandler>
                </View>
            </View>
        </GestureHandlerRootView>
    )
}

export default SlidingCounter

const styles = StyleSheet.create({
    button : {
        height : 70, 
        width : BUTTON_WIDTH, 
        backgroundColor : '#111111', 
        borderRadius : 50,
        alignItems : 'center',
        justifyContent : 'space-evenly',
        flexDirection : 'row'
    },
    circle : {
        height : 50,
        width: 50,
        backgroundColor : '#232323',
        borderRadius : 25,
        position : 'absolute',
        justifyContent : 'center',
        alignItems: 'center'
    },
    countText : {
        fontSize : 25,
        color : 'white',
    }
})